<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Choice;

class QuizCore extends Controller
{

    public function index()
    {
        return view('quiz.index');
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function createQuestion($testID)
    {
        return view('quiz.createQuestion', ['testID' => $testID]);
    }

    public function store(Request $request)
    {
        $testModel = new Test();
        $serverTime = now();

        $testModel->name = $request->input('name');
        $testModel->subject = $request->input('subject');
        $testModel->date = $serverTime;

        $testModel->save();

        $testID = $testModel->id;
        return redirect()->route('quiz.createQuestion', ['testID' => $testID]);
        
    }

    // 문제 생성
	public function ajax_QuestionStore(Request $request)
	{
        $testID = $request->input('testID');
        $number = $request->input('number');
        
        if ($number == 1)
        {
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

    // 문제 업데이트
	public function ajax_QuestionUpdate(Request $request)
	{
        $testID = $request->input('testID');

            //$check = Question::where('number', '1')->where('testID', $testID)->first();

        $questionModel = new Question();
        $questionModel->testID = $request->input('testID');
        $questionModel->number = $request->input('number');
        $questionModel->save();

        $questionID = $questionModel->id;

        $response = [
            'success' => true,
            'questionID' => $questionID,
        ];

		return response()->json($response);
	}

    // 선택지 생성
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

    // 선택지 삭제
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

    // 문제&선택지 초기화
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
