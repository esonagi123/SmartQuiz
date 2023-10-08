<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class Account extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) {
            return view('account.register');
        } else {
            return back()->with('already_login', '이미 로그인 되었습니다.');
        }
    
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'uid' => 'required|unique:users|regex:/^[a-z0-9_-]{5,20}$/',
            'email' => 'required|unique:users|email|max:255',
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-z\d])[a-z\d@$!%*?&]{8,16}$/i', // 최소 8자 이상, 최대 16자 이하의 문자열, 적어도 하나의 소문자 (a-z) 또는 숫자 (\d)
            ],
            'nickname' => 'required|unique:users|regex:/^[\p{L}a-zA-Z0-9]{2,8}$/u',
        ], [
            'nickname.required' => '닉네임을 입력하세요.',
            'nickname.unique' => '사용 중인 닉네임 입니다.',
            'nickname.regex' => '닉네임은 2~8자 이내의 한글과 영문 대/소문자만 사용 가능합니다.',
            'uid.required' => '아이디를 입력하세요.',
            'uid.unique' => '사용 중인 아이디 입니다.',
            'uid.regex' => '아이디는 영문 소문자, 숫자, 밑줄(_), 하이픈(-)만 사용 가능하며 5~20자 이내로 입력하세요.',
            'email.required' => '이메일을 입력하세요.',
            'email.unique' => '사용 중인 이메일 주소입니다.',
            'email.email' => '올바른 이메일 주소를 입력하세요.',
            'email.max' => '이메일 주소는 255자 이내로 입력하세요.',
            'password.required' => '비밀번호를 입력하세요.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'password.regex' => '비밀번호는 8~16자 이내, 소문자와 숫자로 구성되어야 합니다.',
        ]);
    
        $user = new User;
        $user->uid = $request->input('uid');
        $user->nickname = $request->input('nickname');
        $user->avatar = "0";
        $user->email = $request->input('email');
    
        // 비밀번호를 해시화하여 저장
        $hashedPassword = bcrypt($request->input('password'));
        $user->password = $hashedPassword;
    
        $user->save();
    
        return redirect()->route('login')->with('success', '회원가입이 완료되었습니다.\n로그인 해주세요.');
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
