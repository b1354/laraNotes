<?php

namespace App\Http\Controllers;

use App\Models\Post; // tambahkan statement import model
use Illuminate\Support\Str; // digunakan untuk membuat slug di line 36
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() 
    {
        //sintaks dari method index dari class PostController
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function create ()
    {
        // sintaks untuk uri GET "/post/create"
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // sintaks untuk uri POST /post/create
        $this->validate($request, [
            'title' => 'required|string|max:155',
            'content' => 'required',
            'status' => 'required',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'slug' => Str::slug($request->title)

        ]);

        if ($post) {
            return redirect() 
                -> route('post.index') 
                -> with([
                    'success' => 'New post has been created'
                ]);
        } else {
            return redirect()
                -> back()
                -> withInput()
                -> with([
                    'error' => 'some error occurred, please try again'
                ]);
        }
    }

    public function edit ($id) 
    {
        // sintaks untuk uri GET "/pots/{post}/edit"
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id) 
    {
        // sintaks untuk uri PUT "/post/{post}"
        $this->validate($request, [
            'title' => 'required|string|max:155',
            'content' => 'required',
            'status' => 'required',
        ]);

        $post = Post::findOrFail($id);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'slug' => Str::slug($request->title)
        ]);

        if($post) {
            return redirect()
                -> route('post.index')
                -> with([
                    'success' => 'Post has been updated successfully'
                ]);
        } else {
            return redirect()
                -> back()
                -> withInput()
                -> with([
                    'error' => 'an error occured'
                ]);
        }
    }

    public function destroy($id) 
    {
        $post = Post::findOrFail($id);
        $post->delete();

        if($post) {
            return redirect()
                -> route('post.index')
                -> with([
                    'success' => 'post has been deleted successfully'
                ]);
        } else {
            return redirect()
                -> route('post.index')
                -> with([
                    'error' => 'an error has occured'
                ]);
        }
    }
}
