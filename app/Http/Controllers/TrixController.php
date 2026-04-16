<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class TrixController extends Controller
{
    // Display the Trix editor form
    public function index()
    {
        return view('trix'); // For creating a new post
    }

    // Save new post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required'
        ]);

        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'status' => 1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('trix.posts')->with('success', 'Post created successfully!');
    }

    // Handle image upload from Trix
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $filenameWithExt = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('file')->move(public_path('media'), $fileNameToStore);

            echo asset('media/' . $fileNameToStore);
            exit;
        }
    }

    // List all posts
    public function showPosts()
    {
        $posts = Post::all();
        return view('posts', compact('posts'));
    }

    // Delete a post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

    // Edit a post
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('trix_edit', compact('post'));
    }

    // Update post
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('trix.posts')->with('success', 'Post updated successfully!');
    }

    // Toggle post status
    public function toggleStatus($id)
    {
        $post = Post::findOrFail($id);
        $post->status = $post->status == 1 ? 0 : 1;
        $post->save();

        return redirect()->back()->with('success', 'Post status updated!');
    }
}