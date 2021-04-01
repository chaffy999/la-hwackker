<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{

    public function show(Request $request)
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user = User::where([
            'username' => $request['username'],
        ])->first();
        if ($user !== null) {
            if (Hash::check($request['password'], $user->password)) {
                auth()->login($user); //auth()->loginUsingId($user->id); <-- une reqsuête en plus
                return redirect()->route('user', ['username' => $user->username]);
            }
        }

        return redirect()->route('login')->withErrors(['Invalid credentials']);
    }
}
