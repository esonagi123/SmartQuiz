<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Member;

class Login extends Controller
{
	public function index()
	{
		return view('account.login');
	}

    public function check()
	{
        $uid = request('uid');
		$input_pwd = request('password');
		
		$row = Member::where('uid', $uid)->first();
        if ($row)
		{
			$db_pwd = $row->password;
		} else {
			return back()->with('IDerror', '일치하는 ID가 없습니다');
		}
		if ($row && Hash::check($input_pwd, $db_pwd))
		{
			session()->put('id', $row->id);
            return redirect('main');
		} else {
			return back()->with('PWDerror', '비밀번호가 잘못되었습니다.');
		}
		
        
		
	}

    public function logout(Request $request)
    {
		$request->session()->forget('id');

        return redirect('index');
    }	

}