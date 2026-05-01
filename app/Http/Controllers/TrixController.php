<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class TrixController extends Controller
{
    public function index()
    {
        return view('trix');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:1|max:255', 
    'body' => 'required|min:10'
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

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $file = $request->file('file');
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('media'), $fileNameToStore);

            echo asset('media/' . $fileNameToStore);
            exit;
        }
    }

    public function showPosts(Request $request)
    {
        $search = $request->input('search');

        $posts = Post::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('body', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(5);

        foreach ($posts as $post) {
            $words = str_word_count(strip_tags($post->body));
            $post->word_count = $words;
            $post->read_time = ceil($words / 200);
        }

        return view('posts', compact('posts'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('trix_edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|min:10'
        ]);

        $post = Post::findOrFail($id);
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('trix.posts')->with('success', 'Post updated successfully!');
    }

    public function toggleStatus($id)
    {
        $post = Post::findOrFail($id);
        $post->status = $post->status == 1 ? 0 : 1;
        $post->save();

        return redirect()->back()->with('success', 'Post status updated!');
    }
}