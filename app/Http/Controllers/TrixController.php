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
        return view('trix'); // Return the 'trix.blade.php' view
    }

    // Save post in database
    public function store(Request $request)
    {
        // Validate input fields
        $request->validate([
            'title' => 'required|string|max:255', // Title is required, max 255 chars
            'body' => 'required'                  // Body content is required
        ]);

        // Create a new post in the database
        Post::create([
            'title'      => $request->title,   // Save the post title
            'body'       => $request->body,    // Save the post body (HTML from Trix)
            'status'     => 1,                 // Set default status as Active
            'created_by' => Auth::id(),        // ID of logged-in user, null if not logged in
            'updated_by' => Auth::id()         // ID of last updated by (same as created for new post)
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Post created successfully!');
    }

    // Handle image upload from Trix editor
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) { // Check if a file is attached

            // Get original filename with extension
            $filenameWithExt = $request->file('file')->getClientOriginalName();

            // Extract filename without extension
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get file extension
            $extension = $request->file('file')->getClientOriginalExtension();

            // Create a unique filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            // Move the uploaded file to public/media folder
            $request->file('file')->move(public_path('media'), $fileNameToStore);

            // Return the image URL to Trix editor
            echo asset('media/' . $fileNameToStore);
            exit; // Stop further execution
        }
    }

    // Display all saved posts
    public function showPosts()
    {
        $posts = Post::all(); // Fetch all posts from the database
        return view('posts', compact('posts')); // Return 'posts.blade.php' with data
    }
}
