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
        return view('quiz.createQuestion');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
