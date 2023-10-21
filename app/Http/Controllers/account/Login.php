<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class Login extends Controller
{

	public function index()
	{
		if (!Auth::check()) {
			return view('account.login');
		} else {
			return back()->with('already_login', '이미 로그인 되었습니다.');
		}
	}

	protected function check()
	{
		$uid = request('uid');
		$input_pwd = request('password');
		$remember = request('remember');
		
		if ($uid && $input_pwd) {
			// 아이디와 비밀번호를 입력하면 실행
			$row = User::where('uid', $uid)->first();
			if ($row) {
				// 일치하는 uID가 있을 경우
				$db_pwd = $row->password; // DB에서 비밀번호 가져와서 dp_pwd 변수에 저장
			} else {
				return back()->with('IDerror', '일치하는 ID가 없습니다')->withInput();
			}
	
			if ($row && Hash::check($input_pwd, $db_pwd)) {
				// 입력한 비밀번호와 DB에서 가져온 비번이 일치할 경우
				
				$credentials = [
					'uid' => $uid,
					'password' => $input_pwd,
				];

				if (Auth::attempt($credentials, $remember)) {
					// session()->put('user_id', $row->id); // user_id 세션 키 저장
					// return redirect('quiz'); // 퀴즈 홈으로 리다이렉트
					return redirect()->intended('/');
				} else {
					return back()->with('PWDerror', '아이디 또는 비밀번호가 잘못되었습니다.')->withInput();
				}
			} else {
				return back()->with('PWDerror', '비밀번호가 잘못되었습니다.')->withInput();
			}
		} else {
			return back()->with('error', '아이디와 비밀번호를 입력하세요.')->withInput();
		}
	}

	public function logout(Request $request)
	{
		// 로그인 기억하기를 사용한 경우 쿠키를 삭제
		if (Auth::check() && $request->hasCookie('remember_web_59')) {
			$cookie = cookie('remember_web_59', null, -1);
			return redirect('quiz')->withCookie($cookie);
		}
	
		// Laravel Auth에서 로그아웃
		Auth::logout();
	
		// 세션에서 user_id 삭제
		// $request->session()->forget('user_id');
	
		return redirect('quiz');
	}

}