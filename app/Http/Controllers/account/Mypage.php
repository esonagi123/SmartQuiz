<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Mypage extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view('mypage.index');
        } else {
            return redirect('quiz');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email|email|max:255',
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-z\d])[a-z\d@$!%*?&]{8,16}$/i', // 최소 8자 이상, 최대 16자 이하의 문자열, 적어도 하나의 소문자 (a-z) 또는 숫자 (\d)
            ],
            'nickname' => 'required|unique:users,nickname|regex:/^[\p{L}a-zA-Z0-9]{2,8}$/u',
        ], [
            'nickname.required' => '닉네임을 입력하세요.',
            'nickname.unique' => '사용 중인 닉네임 입니다.',
            'nickname.regex' => '닉네임은 2~8자 이내의 한글과 영문 대/소문자만 사용 가능합니다.',
            'email.required' => '이메일을 입력하세요.',
            'email.unique' => '사용 중인 이메일 주소입니다.',
            'email.email' => '올바른 이메일 주소를 입력하세요.',
            'email.max' => '이메일 주소는 255자 이내로 입력하세요.',
            'password.required' => '비밀번호를 입력하세요.',
            'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
            'password.regex' => '비밀번호는 8~16자 이내, 소문자와 숫자로 구성되어야 합니다.',
        ]);

        $userModel = User::find($id);

    }
}