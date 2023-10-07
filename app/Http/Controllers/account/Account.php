<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Member;

class Account extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('account.register');
    
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'uid' => 'required|unique:members|regex:/^[a-z0-9_-]{5,20}$/',
            'email' => 'required|unique:members|email|max:255',
            'password' => 'required|confirmed|min:8|max:16',
            'nickname' => 'required|regex:/^[a-zA-Z가-힣]{2,8}$/',
        ], [
            'nickname.required' => '닉네임을 입력하세요.',
            'nickname.regex' => '닉네임은 2~8자 이내의 한글과 영문 대/소문자만 사용 가능합니다.',
            'uid.required' => '아이디를 입력하세요.',
            'uid.unique' => '이미 사용 중인 아이디 입니다.',
            'uid.regex' => '아이디는 영문 소문자, 숫자, 밑줄(_), 하이픈(-)만 사용 가능하며 5~20자 이내로 입력하세요.',
            'email.required' => '이메일을 입력하세요.',
            'email.unique' => '이미 사용 중인 이메일 주소입니다.',
            'email.email' => '올바른 이메일 주소를 입력하세요.',
            'email.max' => '이메일 주소는 255자 이내로 입력하세요.',
            'password.required' => '비밀번호를 입력하세요.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'password.min' => '비밀번호는 8~16자 이내로 입력하세요.',
            'password.max' => '비밀번호는 8~16자 이내로 입력하세요.',
        ]);
    
        $user = new Member;
        $user->uid = $request->input('uid');
        $user->nickname = $request->input('nickname');
        $user->email = $request->input('email');
    
        // 비밀번호를 해시화하여 저장
        $hashedPassword = bcrypt($request->input('password'));
        $user->password = $hashedPassword;
    
        $user->save();
    
        return redirect()->route('login')->with('success', '회원가입 완료');
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
