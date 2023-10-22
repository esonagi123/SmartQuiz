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
        $myQuizs = null;

        if (Auth::check()) {
            // 로그인 했을 경우
            $user = Auth::user();
            $nickname = $user->nickname;

            $myQuizs = Test::where('uid', $user->uid)->orderby('updated_at', 'desc')->take(3)->get();

            if ($myQuizs->isEmpty()) {
                // 결과가 없는 경우
                $myQuizs = null;
            }
        }

        $publicQuizs = Test::where('secret', 'N')->where('incomplete', 'N')->orderby('updated_at', 'desc')->get();

        return view('quiz.index', [
            'nickname'=> $nickname,
            'myQuizs' => $myQuizs,
            'quizs' => $publicQuizs,
        ]);
    }

    public function publicQuizIndex()
    {
        $quizs = Test::where('secret', 'N')->where('incomplete', 'N')->orderby('updated_at', 'desc')->get();

        return view('quiz.publicQuiz', [
            'quizs' => $quizs,
        ]);

    }

    public function myQuizIndex()
    {
        $user = Auth::user();
        $uid = $user->uid;
        $myQuizs = Test::where('uid', $uid)->orderby('updated_at', 'desc')->get();

        return view('quiz.myQuiz', [
            'myQuizs' => $myQuizs,
        ]);

    }    

    // 시험 풀기
    public function solve($testID, $type)
    {
        if ($type == "1") {
            // 1 = 일반 출제
            $testModel = Test::find($testID);
            $questions = Question::where('testID', $testID)->orderby('number', 'asc')->get();
            $questionCount = Question::where('testID', $testID)->count();
            
            $choices = [];
            $value = [];
            
            
            foreach ($questions as $question) {
                // 문제 수 만큼 순회하면서 동적으로 choices 배열을 생성
                $choices[$question->id] = Choice::where('qid', $question->id)->orderby('number', 'asc')->get();
                
                // 선택지들을 담을 배열을 동적으로 만들고 초기화
                $value[$question->number] = [];
                
                foreach ($choices[$question->id] as $choice) {
                    // 동적으로 만들어진 choices 배열을 순회

                    $value[$question->number][] = $choice->number;
                }
            }

        } elseif ($type == "2") {
            // 2 = 랜덤 출제

            if (!Auth::check()) {
                // session()->flash('not_auth', '로그인이 필요합니다.');
                return redirect('quiz/solve/' . $testID . '/type1');
            } else {
                $testModel = Test::find($testID);
                $questions = Question::where('testID', $testID)->inRandomOrder()->get();
                $questionCount = Question::where('testID', $testID)->count();
                
                $choices = [];
                $value = [];
                
                
                foreach ($questions as $question) {
                    // 문제 수 만큼 순회하면서 동적으로 choices 배열을 생성
                    $choices[$question->id] = Choice::where('qid', $question->id)->inRandomOrder()->get();
                    
                    // 선택지들을 담을 배열을 동적으로 만들고 초기화
                    $value[$question->number] = [];
                    
                    foreach ($choices[$question->id] as $choice) {
                        // 동적으로 만들어진 choices 배열을 순회
    
                        $value[$question->number][] = $choice->number;
                    }
                }  
            }
        }
        
        $result = [
            'questions' => $questions,
            'questionCount' => $questionCount,
            'choices' => $choices,
        ];

        return view('quiz.solveTest',
        [
            'testID' => $testID,
            'testModel' => $testModel,
            'items' => $result,
            'value' => $value,
            'type' => $type,

        ]);
    }

    // 시험 결과
    public function result($testID, Request $request)
    {
        $type = $request->input('type');

        $maximumScore = 100;
        $score = 0;
        

        $test = Test::find($testID);
        $questions = Question::where('testID', $test->id)->orderby('number', 'asc')->get();
        $questionCount = Question::where('testID', $test->id)->count();

        $wrongQuestion = [];
        $returnInputs = null;
        $returnAnswers = null;
        $answerForCheck = null;

        // 각 문제당 배점 계산
        if ($questionCount > 0) {
            $allocation = $maximumScore / $questionCount;
        }

        // 채점을 위해 각 문제 가져오기
        foreach ($questions as $question) {
            $choices = Choice::where('qid', $question->id)->orderby('number', 'asc')->get(); // 각 문제에 대한 선택지들
            $count = Choice::where('qid', $question->id)->count(); // 각 문제에 대한 선택지 갯수

            if ($question->gubun == "1") {
                
                foreach ($choices as $choice) { // 각 문제에 대한 선택지 가져오기

                    // $question->number를 이름으로 갖는 다차원 배열 생성
                    // 동적으로 form에서 받은 input 값을 inputAnswers[$question->number] 배열에 추가
                    $inputAnswers[$question->number][] = $request->input('Q'. $question->number . 'answer' . $choice->number);

                    // answer[$question->number] 배열에 정답을 추가 ( 1번 문제에 정답이 5번일 경우 ex. { 1 : null, null, null, null, 5 } )
                    $answer[$question->number][] = $choice->answer; // 정답 확인 후 초기화 되는 배열
                    $answerForCheck[$question->number][] = $choice->answer; // 뷰로 전달할 배열 (정답 확인)
                }
                
                if ($inputAnswers == $answer) {
                    // 정답이 일치하면 점수 부여
                    $score += $allocation;
                } else {
                    // 틀리면
                    $wrongQuestion[] = [
                        'number' => $question->number,
                        'wrong' => $question->number,
                    ];
                }

                $returnInputs[] = [
                    'number' => $question->number,
                    'input' => $inputAnswers[$question->number],
                    
                ];
                
                // \Log::info("input: " . json_encode($wrongQuestion));
                // \Log::info("input: " . json_encode($inputAnswers));
                // \Log::info("input: " . json_encode($returnInputs));
                    \Log::info("DB: " . json_encode($returnAnswer));
                
                // 배열 초기화 (안하면 1 : ... , 2 : ... 이런식으로 문제를 순회할 때마다 늘어나 버린다.)
                $inputAnswers = [];
                $answer = [];

                // $wrongQuestion = [];
            } elseif ($question->gubun == "2") {
                $shortAnswer = $question->answer;
                $userAnswer = $request->input('shortAnswer' . $question->number);

                
                $correctAnswers = explode(',', str_replace(' ', '', $shortAnswer));
                $userAnswers = explode(',', str_replace(' ', '', $userAnswer));

                // 각각의 배열의 문자열을 소문자로 변환
                $correctAnswers = array_map('strtolower', $correctAnswers);
                $userAnswers = array_map('strtolower', $userAnswers);

                // 정답 중 하나라도 사용자의 답변과 일치하는지 확인
                if (count(array_intersect($correctAnswers, $userAnswers)) > 0) {
                    // 하나라도 일치하는 경우
                    $score += $allocation;
                } else {
                    // 일치하는 것이 없는 경우
                    $wrongQuestion[] = [
                        'number' => $question->number,
                        'wrong' => $question->number,
                    ];
                }
                
                // $questionNumber = $question->number;
                // $returnAnswers = [
                //     $questionNumber => $userAnswer
                // ];

                $returnAnswers[$question->number] = [
                    // 'number' => $question->number,
                    'value' => $userAnswer,
                    
                ];

                // \Log::info("return: " . json_encode($returnAnswers));
                // $returnAnswers[$question->number] = [];
                // $returnAnswers[$question->number][] = $request->input('shortAnswer');
            }
        }

        // result 뷰에 뿌리기 위해 문제 불러오기
        foreach ($questions as $question) {
                // 문제 수 만큼 순회하면서 동적으로 choices 배열을 생성
                $choices[$question->id] = Choice::where('qid', $question->id)->orderby('number', 'asc')->get();
                
                // 선택지들을 담을 배열을 동적으로 만들고 초기화
                $value[$question->number] = [];
                
                foreach ($choices[$question->id] as $choice) {
                    // 동적으로 만들어진 choices 배열을 순회

                    $value[$question->number][] = $choice->number;
                }
        }

        $result = [
            'questions' => $questions,
            'questionCount' => $questionCount,
            'choices' => $choices,
        ];

        return view('quiz.result', [
            'testID' => $testID,
            'score' => $score,
            'wrongQuestions' => $wrongQuestion,
            'returnInputs' => $returnInputs,
            'returnAnswers' => $returnAnswers,
            'testModel' => $test,
            'items' => $result,
            'value' => $value,
            'type' => $type,
            'answer' => $answerForCheck,
        ]);
    }

    public function create()
    {
        return view('quiz.create');
    }

    // 문제 생성 뷰
    public function createQuestion($testID)
    {
        $user = Auth::user();
        $testModel = Test::find($testID);
        if ($testModel && $testModel->uid == $user->uid) {
            return view('quiz.createQuestion', ['testID' => $testID]);
        } else {
            return redirect('quiz');
        }
        
    }

    // 문제 수정 뷰
    public function editQuestion($testID)
    {
        $user = Auth::user();
        $testModel = Test::find($testID);
        if ($testModel && $testModel->uid == $user->uid) {
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
        } else {
            return redirect('quiz');
        }
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
        if ($request->input('secret') == 'Y') {
            $testModel->secret = $request->input('secret');
        } else {
            $testModel->secret = 'N';
        }
        $testModel->incomplete = "Y";
        

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

                $testModel = Test::find($testID);
                $testModel->incomplete = "Y";
                $testModel->save();

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
            $questionModel->gubun = "선택하세요";
            $questionModel->save();
    
            $questionID = $questionModel->id;
    
            $testModel = Test::find($testID);
            $testModel->incomplete = "Y";
            $testModel->save();

            $response = [
                'success' => true,
                'questionID' => $questionID,
            ];
        }

		return response()->json($response);
	}

    // 퀴즈 Update
	public function ajax_QuizUpdate(Request $request)
	{
        $testModel = Test::find($request->input('testID'));
        if ($testModel) {
            $testModel->name = $request->input('quiz_name');
            $testModel->subject = $request->input('subject');
            if ($request->input('secret') == 'Y') {
                $testModel->secret = $request->input('secret');
            } else {
                $testModel->secret = 'N';
            }

            $testModel->save();

            $response = [
                'success' => true,
            ];
        }

		return response()->json($response);
	}

    // 전체 저장
	public function ajax_QuestionUpdate(Request $request)
	{
        $questionID = $request->input('questionID');
        $number = $request->input('number');

        // 동적 생성된 input name을 위한 코드
        $name = 'name' . $number; // 문제명
        $gubun = 'gubun' . $number; // 문제 유형
        $shortAnswer = 'shortAnswer' . $number;

        // 퀴즈 Update
        $testModel = Test::find($request->input('testID'));

        if ($request->input('quiz_name')) {
            $testModel->name = $request->input('quiz_name');
        }
        if ($request->input('subject')) {
            $testModel->subject = $request->input('subject');
        }
        if ($request->input('secret') == 'Y') {
            $testModel->secret = $request->input('secret');
        } else {
            $testModel->secret = 'N';
        }
        $testModel->incomplete = 'N';
        $testModel->updated_at = now();
        $testModel->save(); 

        // 문제 정보 Update
        $questionModel = Question::find($questionID);
        $questionModel->question = $request->input($name);
        $questionModel->answer = $request->input($shortAnswer);
        
        if ($request->input($gubun) == "선택하세요") {
            $questionModel->gubun = null;
        } else {
            $questionModel->gubun = $request->input($gubun);
        }
        
        $questionModel->save();

        // 선택지 정보 Update
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

    // 문제유형 자동 업데이트 (select onchange 발생 시)
    public function ajax_GubunUpdate(Request $request)
    {
        $questionID = $request->input('questionID');
        $gubun = $request->input('gubun');

        $questionModel = Question::find($questionID);

        $questionModel->gubun = $gubun;

        $questionModel->save();

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

            $question = Question::find($request->input('questionID'));
            $question->gubun = "1";
            $question->save();

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
        if ($request->input('type') == "1") {
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
        } elseif ($request->input('type') == "2") {
            if ($request->input('questionID')) {
                $questionID = $request->input('questionID');
                $choices = Choice::where('qid', $questionID)->get();

                foreach ($choices as $choice) {
                    $choice->delete();
                }

                $question = Question::find($request->input('questionID'));
                $question->gubun = "2";
                $question->save();

                $response = [
                    'success' => true,
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => '실패했습니다.',
                ];
            }
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
