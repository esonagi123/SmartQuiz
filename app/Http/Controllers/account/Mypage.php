<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function checkPassword(Request $request)
    {
        $request->validate( ['pwd' => 'required',], ['pwd.required' => '비밀번호를 입력하세요.',] );

        $user = Auth::user();
        $userModel = User::find($user->id);

        if ($userModel) {
            $hashedPassword = $userModel->password;
            $inputPassword = $request->input('pwd');

            if (Hash::check($inputPassword, $hashedPassword)) {
                session()->flash('ok', '');
                return redirect()->route('mypage.edit');
            } else {
                session()->flash('error', '비밀번호가 올바르지 않습니다.');
                return back();
            }
        } else {
            return redirect('quiz');
        }
    }

    public function edit()
    {
        if (session()->has('ok')) {
            return view('mypage.edit'); 
        } else {
            return redirect()->route('mypage');
        }
        
    }

    public function update(Request $request)
    {
        if ($request->input('password')) {
            $validator = Validator::make($request->all(), [
                'email' => 'sometimes|email|max:255|unique:users,email,' . $request->user()->id,
                'password' => 'sometimes|confirmed|regex:/^(?=.*[a-z\d])[a-z\d@$!%*?&]{8,16}$/i',
                'nickname' => 'sometimes|regex:/^[\p{L}a-zA-Z0-9]{2,8}$/u',
            ], [
                'nickname.regex' => '닉네임은 2~8자 이내의 한글과 영문 대/소문자만 사용 가능합니다.',
                'email.email' => '올바른 이메일 주소를 입력하세요.',
                'email.max' => '이메일 주소는 255자 이내로 입력하세요.',
                'email.unique' => '사용 중인 이메일 주소입니다.',
                'password.confirmed' => '비밀번호 확인이 일치하지 않습니다.',
                'password.regex' => '비밀번호는 8~16자 이내, 소문자와 숫자로 구성되어야 합니다.',
            ]);
    
            if ($validator->fails()) {
                session()->flash('ok', '');
                return redirect()->route('mypage.edit')  // Replace with your route name
                    ->withErrors($validator)
                    ->withInput();
            }
    
            $user = User::find($request->input('id'));
            $user->nickname = $request->input('nickname');
            $user->email = $request->input('email');
    
            // 비밀번호를 해시화하여 저장
            if ($request->input('password')) { 
                $hashedPassword = bcrypt($request->input('password'));
                $user->password = $hashedPassword;
            }
        } else {
            $validator = Validator::make($request->all(), [
                'email' => 'sometimes|email|max:255|unique:users,email,' . $request->user()->id,
                'nickname' => 'sometimes|regex:/^[\p{L}a-zA-Z0-9]{2,8}$/u',
            ], [
                'nickname.regex' => '닉네임은 2~8자 이내의 한글과 영문 대/소문자만 사용 가능합니다.',
                'email.email' => '올바른 이메일 주소를 입력하세요.',
                'email.max' => '이메일 주소는 255자 이내로 입력하세요.',
                'email.unique' => '사용 중인 이메일 주소입니다.',
            ]);
    
            if ($validator->fails()) {
                session()->flash('ok', '');
                return redirect()->route('mypage.edit')  // Replace with your route name
                    ->withErrors($validator)
                    ->withInput();
            }

            $user = User::find($request->input('id'));
            $user->nickname = $request->input('nickname');
            $user->email = $request->input('email');
        }

        $user->save();

        return redirect()->route('mypage')->with('success', '수정이 완료되었습니다.');
    }

    public function updateAvatar(Request $request)
    {
        $userModel = User::find($request->input('id'));
        $userModel->avatar = $request->input('avatar');
        $userModel->save();

        return redirect('mypage');
    }

    public function destory()
    {
        
    }
}