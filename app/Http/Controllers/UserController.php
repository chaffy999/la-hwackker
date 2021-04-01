<?php

namespace App\Http\Controllers;

use App\Models\Hwack;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GrahamCampbell\Markdown\Facades\Markdown;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('username')) {
            $userProfile = User::all()->where('username', $request->get('username'))->first();
            $user = auth()->user();
            if (($request->has('private') && $request['private'] == 'true' && $user->is_admin == 1) || $user->id == $userProfile->id) {
                return view('user', [
                    'user' => $user,
                    'userProfile' => $userProfile,
                    'hwacks' => Hwack::where('user_id', $userProfile->id)->latest()->paginate(100),
                    'followed' => $userProfile->followed,
                ]);
            }
            return view('user', [
                'user' => $user,
                'userProfile' => $userProfile,
                'hwacks' => Hwack::where('user_id', $userProfile->id)->where('private', 0)->latest()->paginate(100),
                'followed' => $userProfile->followed,
            ]);
        }

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('home');
        }

        if ($request->has('search')) {
            $keyword = $request['search'];
            return view('user', [
                'user' => $user,
                'userProfile' => $user,
                'hwacks' => Hwack::where('content', 'like', "%{$keyword}%")->latest()->paginate(100),
                'followed' => $user->followed,
            ]);
        }

        return view('user', [
            'user' => $user,
            'userProfile' => $user,
            'hwacks' => Hwack::where('private', 0)->latest()->paginate(100),
            'followed' => $user->followed,
        ]);
    }

    public function createHwack(Request $request)
    {

        $request->validate([
            'image' => 'mimes:jpeg,jpg,png,gif,webp',
            'content' => 'required|min:1|max:500',
            'private' => 'boolean',
        ]);

        $user = $request->user();
        if (!empty($request['image'])) {
            $image = $request->file('image')->storeAs('img/hwacks', Str::uuid(), ['disk' => 'public']);
        } else {
            $image = '';
        }
        $isPrivate = $request->has('private');
        Hwack::create([
            'user_id' => $user->id,
            'image' => $image,
            'content' => Markdown::convertToHtml($request['content']),
            'private' => $isPrivate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user', ['username' => $user->userame]);
    }
}
