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
	
		if ($request->input('testID')) {
            $questionModel = new Question();
            $questionModel->testID = $request->input('testID');
            $questionModel->save();

            $questionID = $questionModel->id;

            $response = [
                'success' => true,
                'questionID' => $questionID,
            ];
            
		} else {
			$response = [
				'success' => false,
				'message' => '실패했습니다.',
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
