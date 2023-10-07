<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Choice;

class QuizCore extends Controller
{

    public function index()
    {
        $nickname = null;
        $tests = null;

        if (Auth::check()) {
            // 로그인 했을 경우
            $user = Auth::user();
            $nickname = $user->nickname;

            $tests = Test::where('uid', $user->uid)->orderby('date', 'desc')->get();

            if ($tests->isEmpty()) {
                // 결과가 없는 경우
                $tests = null;
            }
        }

        return view('quiz.index', [
            'nickname'=> $nickname,
            'tests' => $tests,
        ]);
    }

    public function create()
    {
        return view('quiz.create');
    }

    // 문제 생성 뷰
    public function createQuestion($testID)
    {
        return view('quiz.createQuestion', ['testID' => $testID]);
    }

    // 문제 수정 뷰
    public function editQuestion($testID)
    {
        $testModel = Test::find($testID);
        $questions = Question::where('testID', $testID)->orderby('number', 'asc')->get();
        $questionCount = Question::where('testID', $testID)->count();
        
        $choices = [];
        $value = [];
        
        foreach ($questions as $question) {
            $choices[$question->id] = Choice::where('qid', $question->id)->orderby('number', 'asc')->get();
            
            // 질문에 대한 선택지 번호 배열을 초기화
            $value[$question->number] = [];
            
            foreach ($choices[$question->id] as $choice) {
                $value[$question->number][] = $choice->number;
            }
        }
        
        $result = [
            'questions' => $questions,
            'questionCount' => $questionCount,
            'choices' => $choices,
        ];

        return view('quiz.editQuestion',
        [
            'testID' => $testID,
            'testModel' => $testModel,
            'items' => $result,
            'value' => $value,

        ]);
    }

    // 시험 생성 
    public function store(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
                $uid = $user->uid;
            }
        }

        $testModel = new Test();
        $serverTime = now();

        $testModel->uid = $uid;
        $testModel->name = $request->input('name');
        $testModel->subject = $request->input('subject');
        $testModel->date = $serverTime;

        $testModel->save();

        $testID = $testModel->id;
        return redirect()->route('quiz.createQuestion', ['testID' => $testID]);
        
    }

    // 문제 생성 (Ajax)
	public function ajax_QuestionStore(Request $request)
	{
        $testID = $request->input('testID');
        $number = $request->input('number');
        
        if ($number == 1) {
            // cardCount(number)가 1일 경우
            // DB 조회
            $check = Question::where('number', '1')->where('testID', $testID)->first();
            if (!$check) {
                // 1번 문제가 없으면 실행
                $questionModel = new Question();
                $questionModel->testID = $request->input('testID');
                $questionModel->number = $request->input('number');
                $questionModel->save();
    
                $questionID = $questionModel->id;

                $response = [
                    'success' => true,
                    'questionID' => $questionID,
                ];
            } else {
                // 1번 문제가 있으면
                $response = [
                    'success' => false,
                    'message' => '이미 1번 문제가 있습니다.'
                ];
                
            }
        } else {
            // cardCount(number)가 1이 아닐 경우 (addCard2)
            $questionModel = new Question();
            $questionModel->testID = $request->input('testID');
            $questionModel->number = $request->input('number');
            $questionModel->save();
    
            $questionID = $questionModel->id;
    
            $response = [
                'success' => true,
                'questionID' => $questionID,
            ];
        }

		return response()->json($response);
	}

    // 문제&선택지 업데이트 updateQuestion() & save() (Ajax)
	public function ajax_QuestionUpdate(Request $request)
	{
        $questionID = $request->input('questionID');
        $number = $request->input('number');

        // 동적 생성된 input name을 위한 코드
        $name = 'name' . $number; // 문제명
        $gubun = 'gubun' . $number; // 문제 유형

        // 문제 정보 Update
        $questionModel = Question::find($questionID);
        $questionModel->question = $request->input($name);
        $questionModel->gubun = $request->input($gubun);
        
        $questionModel->save();

        // 선택지 정보 Update
        // $choiceCount = Choice::where('qid', $questionID)->select('number')->count();

        $choices = Choice::where('qid', $questionID)->orderBy('number', 'asc')->get();
        foreach($choices as $choice) {

            // $choiceModel = Choice::where('qid', $questionID)->where('number', $cNumber)->first();
            
            $inputValue = 'choice' . $choice->number;
            $answer = 'answer' . $choice->number;

            $choice->content = $request->input($inputValue);
            $choice->answer = $request->input($answer);

            $choice->save();
        }

        $response = [
            'success' => true,
        ];

		return response()->json($response);
	}

    // 문제 삭제 후 선택지도 삭제
    public function ajax_QuestionDestroy(Request $request)
    {
        $testID = $request->input('testID');
        $number = $request->input('number');

        // 문제 삭제
        $question = Question::where('testID', $testID)->where('number', $number)->first();
        if ($question) {
            $question->delete();
        }

        // 선택지가 있으면 삭제
        $choices = Choice::where('qid', $question->id)->get();
        if ($choices) {
            foreach ($choices as $choice) {
                $choice->delete();
            }
        }

        $response = [
            'success' => true,
        ];

        return response()->json($response);

    }

    // 선택지 생성 (Ajax)
	public function ajax_ChoiceStore(Request $request)
	{
	
		if ($request->input('questionID')) {
            $choiceModel = new Choice();

            $choiceModel->qid = $request->input('questionID');
            $choiceModel->number = $request->input('number');
            $choiceModel->valid = 2;

            $choiceModel->save();

            $choiceID = $choiceModel->id;

            $response = [
                'success' => true,
                'choiceID' => $choiceID,
            ];
            
		} else {
			$response = [
				'success' => false,
				'message' => '실패했습니다.',
			];
		}
		return response()->json($response);
	}

    // 선택지 삭제 (Ajax)
	public function ajax_ChoiceDestroy(Request $request)
	{
	
		if ($request->input('choiceID')) {
            $choiceID = $request->input('choiceID');
            $questionID = $request->input('questionID');
            $choice = Choice::where('number', $choiceID)->where('qid', $questionID)->first();

			if (!$choice)
			{
                $response = [
                    'success' => false,
                    'message' => '삭제할 대상을 찾을 수 없습니다.',
                ];				
                return response()->json($response);
            }
            
            $choice->delete();

            $response = [
                'success' => true,
            ];
            
		} else {
			$response = [
				'success' => false,
				'message' => '실패했습니다.',
			];
		}
		return response()->json($response);
	}    

    // 문제&선택지 초기화 (Ajax)
    public function ajax_reset(Request $request)
    {
		if ($request->input('testID')) {
            $testID = $request->input('testID');
            
            $questions = Question::where('testID', $testID)->get();
            $choices = Choice::leftJoin('questions', 'choices.qid', '=', 'questions.id')
            ->select('choices.*', 'questions.testID')
            ->where('questions.testID', $testID)
            ->get();
            
            foreach ($choices as $choice) {
                $choice->delete();
            }

            foreach ($questions as $question) {
                $question->delete();
            }

            $response = [
                'success' => true,
            ];
		} else {
			$response = [
				'success' => false,
				'message' => '초기화 실패',
			];
		}

		return response()->json($response);
    }
}
