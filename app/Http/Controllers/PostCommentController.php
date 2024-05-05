<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostCommentController extends Controller
{
    public function create(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validator->errors()], 400);
        }

        $comment = PostComment::create([
            'post_id' => $id,
            'user_id' => Auth::user()->id,
            'message' => $request->message
        ]);

        return response()->json(['message' => 'Comment Created Successfully', 'comment' => $comment]);
    }

    public function destroy($id)
    {
        $comment = PostComment::find($id);
        $comment->delete();

        return response()->json(['message' => 'Comment Deleted Successfully']);
    }
}
