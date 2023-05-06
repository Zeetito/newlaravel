<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function ShowEditForm(Post $post){

        return view('edit-post',['post'=>$post]);
    }

    public function actuallyUpdate(Post $post,Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->user()->id;

        $post->update($incomingFields);
        // return back()->with('success','Post Successfull updated'); will take you to the previous url/page
        return redirect("/post/{$post->id}")->with('success','Post Successfully Updated');
    }

    public function delete(Post $post){
        // if(auth()->user()->cannot('delete',$post)){
        //     return "You cannot delte this post";
        // }
        $post->delete();
        return redirect('profile/'.auth()->user()->username)->with('success','Post Deleted Successfully');
    }

    public function VeiwSinglePost(Post $post){
        $post['body'] = strip_tags(Str::markdown($post->body),'<ul><ol><li><strong><em><h3><br><p>');
        return view('single-post',['post'=>$post]);
    }

    public function StoreNewPost(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->user()->id;
        // return "Hey Post stored";
         $NewPost = Post::create($incomingFields);
         return redirect("/post/{$NewPost->id}")->with('success','New Post Successfully Created');
    }

    public function ShowCreateForm(){
        return view('create-post');
    }
}
