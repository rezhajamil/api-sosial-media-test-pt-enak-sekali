<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function getFollowers()
    {
        $user_id = Auth::user()->id;

        $followers = Follow::with(['follower'])->where('user_id', $user_id)->get();

        return response()->json(['followers' => $followers]);
    }

    public function getFollowing()
    {
        $user_id = Auth::user()->id;

        $following = Follow::with(['user'])->where('follower_id', $user_id)->get();

        return response()->json(['following' => $following]);
    }

    public function toggleFollow($id)
    {
        $isFollowing = Follow::where('user_id', $id)->where('follower_id', Auth::user()->id)->count();

        if ($isFollowing) {
            $follow = Follow::where('user_id', $id)->where('follower_id', Auth::user()->id)->delete();

            return response()->json(['message' => 'Unfollow Successfully']);
        } else {
            $follow = Follow::create([
                'user_id' => $id,
                'follower_id' => Auth::user()->id
            ]);

            return response()->json(['message' => 'Follow Successfully']);
        }
    }
}
