<?php

namespace App\Http\Controllers;

use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function toggleLike($id)
    {
        $isLiked = PostLike::where('post_id', $id)->where('user_id', Auth::user()->id)->count();

        if ($isLiked) {
            $like = PostLike::where('post_id', $id)->where('user_id', Auth::user()->id)->delete();

            return response()->json(['message' => 'Unlike Successfully']);
        } else {
            $like = PostLike::create([
                'post_id' => $id,
                'user_id' => Auth::user()->id
            ]);

            return response()->json(['message' => 'Like Successfully']);
        }
    }

    public function getMyLikes()
    {
        $likes = PostLike::with('post')->where('user_id', Auth::user()->id)->get();

        return response()->json(['likes' => $likes]);
    }
}
