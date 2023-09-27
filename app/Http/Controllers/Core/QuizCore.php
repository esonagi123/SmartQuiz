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

	public function ajax_QuestionStore(Request $request)
	{
        $testID = $request->input('testID');

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
                $response = [
                    'success' => false,
                    'message' => '오류'
                ];
                
            }

		return response()->json($response);
	}

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

	public function ajax_ChoiceDestroy(Request $request)
	{
	
		if ($request->input('choiceID')) {
            $choiceID = $request->input('choiceID');
            $choice = Choice::where('number', $choiceID)->first();

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
