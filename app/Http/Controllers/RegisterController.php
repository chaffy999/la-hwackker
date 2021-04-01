<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Rules\ValidRecaptcha;

class RegisterController extends Controller
{
    public function show()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'profile_picture' => 'mimes:jpeg,jpg,png,gif,webp|required',
            'username' => 'required|string|min:2|max:12',
            'birth_date' => 'required|before:-14 years',
            'email' => 'required|email',
            'password' => 'required|min:6|max:64',
            'password_confirmation' => 'same:password',
            'facebook_url' => 'required_without:twitter_url',
            'twitter_url' => 'required_without:facebook_url',
            'country' => 'required',
            'g-recaptcha-response' => ['required', new ValidRecaptcha],
        ]);
        $file = $request->file('profile_picture')->storeAs('img/avatars', $request['username'], ['disk' => 'public']);
        $user = User::create([
            'username' => $request['username'],
            'profile_picture' => $file,
            'birth_date' => $request['birth_date'],
            'email' => $request['email'],
            'country' => $request['country'],
            'facebook_url' => $request['facebook_url'],
            'twitter_url' => $request['twitter_url'],
            'password' => Hash::make($request['password']),
        ]);

        auth()->login($user);

        return redirect()->route('user', ['username' => $user->username]);
    }
}
