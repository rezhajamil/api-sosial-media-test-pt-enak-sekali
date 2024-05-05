<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderBy = $request->order ?? 'title';
        $sort = $request->sort ?? 'asc';

        $posts = Post::with(['images', 'likes', 'comments'])->orderBy($orderBy, $sort)->get();

        return response()->json(['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:posts',
            'caption' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'slugs' => Str::slug($request->title),
            'caption' => $request->caption,
        ]);

        foreach ($request->images as $key => $image) {
            $url = $image->store("post_images/$post->id");
            PostImage::create([
                'post_id' => $post->id,
                'url' => $url,
            ]);
        }

        return response()->json(['message' => 'Post Created', 'post' => $post]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post = Post::with(['images', 'likes', 'comments'])->find($post->id);

        if (!$post) {
            return response()->json(['message' => 'Post Not Found']);
        }

        return response()->json(['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:posts,title,' . $post->id,
            'caption' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $post->title = $request->title;
        $post->caption = $request->caption;
        $post->save();

        $old_images = PostImage::where('post_id', $post->id)->get();
        foreach ($old_images as $image) {
            Storage::delete($image->url);
            $image->delete();
        }

        return response()->json(['message' => 'Post Updated', 'post' => $post]);
    }
}
