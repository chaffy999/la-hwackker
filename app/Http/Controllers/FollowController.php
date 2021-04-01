<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addFollow(Request $request)
    {
        $user = $request->user();
        Follow::create([
            'user_id' => $user->id,
            'followed_id' => $request['followed_id'],
        ]);

        $userProfile = User::all()->where('id', $request['followed_id'])->first();

        return redirect()->route('user', ['username' => $userProfile->userame]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFollow(Request $request)
    {
        $user = $request->user();
        Follow::where(['user_id', $user->id], ['followed_id', $request['followed_id']])->delete();

        $userProfile = User::all()->where('id', $request['followed_id'])->first();

        return redirect()->route('user', ['username' => $userProfile->userame]);
    }
}
