# PHP_Laravel12_Use_Trix_Editor

**Project:** Install & use the Trix rich-text editor in Laravel 12

**Purpose:** A beginner-friendly, step-by-step README that shows how to create a Laravel 12 app, add the Trix editor (via CDN or NPM/Vite), save editor content to the database, and (optionally) implement image upload for attachments.

---

## 1) Create the Laravel 12 project

Open your terminal and run (this will create a folder named `PHP_Laravel12_Use_Trix_Editor`):

```bash
composer create-project laravel/laravel PHP_Laravel12_Use_Trix_Editor "12.*"
cd PHP_Laravel12_Use_Trix_Editor
php artisan serve
```

---

## 2) configure .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trix_db
DB_USERNAME=root
DB_PASSWORD=
```

Run migration (Create database): 

```bash
php artisan migrate
```

## 3) Recommended project structure (important files)

```
PHP_Laravel12_Use_Trix_Editor/
│
├── app/
│   └── Http/
│       └── Controllers/
│           └── PostController.php     # Controller to handle CRUD operations for posts
│
├── database/
│   └── migrations/
│       └── 2025_12_12_000000_create_posts_table.php   # Migration file to create 'posts' table
│
├── public/
│   ├── media/           # Folder to store uploaded images from Trix editor
│   └── js/
│       └── attachments.js  # JavaScript file to handle Trix editor image uploads
│
├── resources/
│   └── views/
│       ├── trix.blade.php       # Blade view containing the form to add/create posts using Trix editor
│       └── posts.blade.php      # Blade view to display all saved posts
│
├── routes/
│   └── web.php           # Routes file to define web routes for creating, storing, and showing posts
│
├── .env                  # Environment configuration file (database, app key, etc.)
│
├── package.json          # Node.js dependencies (for Trix or other frontend packages if using NPM)
├── composer.json         # PHP dependencies (Laravel framework, packages, etc.)
└── README.md             # Project documentation and instructions

```

---

## 4) Database migration (posts table)

Create a migration for `posts`:

```bash
php artisan make:migration create_posts_table --create=posts
```

Open the migration and use this schema:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Create the 'posts' table
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Primary key 'id' (auto-increment)

            $table->string('title'); // Title of the post
            $table->longText('body'); // Body content of the post (HTML from Trix editor)

            // Additional fields
            $table->tinyInteger('status')->default(1); // Post status: 1 = active, 0 = inactive
            $table->unsignedBigInteger('created_by')->nullable(); // User ID who created the post (optional)
            $table->unsignedBigInteger('updated_by')->nullable(); // User ID who last updated the post (optional)

            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes

            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the 'posts' table if the migration is rolled back
        Schema::dropIfExists('posts');
    }
};
```

Run migration: 

```bash
php artisan migrate
```

---

## 4) Routes

Add routes to `routes/web.php`:

```php
use App\Http\Controllers\PostsController;

Route::get('/', [PostsController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostsController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostsController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}/edit', [PostsController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostsController::class, 'update'])->name('posts.update');

// Optional upload route for Trix attachments (if implementing uploads)
Route::post('/trix/upload', [PostsController::class, 'uploadAttachment'])->name('trix.upload');
```

---

## 5) Controller (TrixController)

Create the controller:

```bash
php artisan make:controller TrixController
```

Open app/Http/Controllers/TrixController.php and put:

```php
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
```

## 6) Create media Folder

Create a folder where images will be stored:

mkdir public\media

## 7) Model: Post.php 

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes; 
    // HasFactory: Enables factory-based testing and seeding
    // SoftDeletes: Allows "soft deleting" records (adds deleted_at column instead of permanently deleting)

    // Fields that are mass assignable
    protected $fillable = [
        'title',       // Post title
        'body',        // Post content (HTML from Trix editor)
        'status',      // Post status: 1 = active, 0 = inactive
        'created_by',  // ID of the user who created the post
        'updated_by'   // ID of the user who last updated the post
    ];

    // You can also add custom relationships or accessors here if needed
}
```
---


## 7) Blade view: trix.blade.php 

File: resources/views/trix.blade.php

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Trix Editor in Laravel 12</title>

    <!-- CSRF Token for security -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Trix editor CSS -->
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

    <style>
        /* Styling images inside Trix content (default size shown in editor) */
        .trix-content img {
            width: 500px;  /* Fixed width for preview */
            height: 300px; /* Fixed height for preview */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3>Trix Editor with Image Upload</h3>

    <!-- Form to submit new post -->
    <form action="{{ route('trix.store') }}" method="POST">
        @csrf  <!-- CSRF protection -->

        <div class="mb-3">
            <strong>Title:</strong>
            <!-- Post title input -->
            <input type="text" name="title" class="form-control" placeholder="Title">
        </div>

        <div class="mb-3">
            <strong>Body:</strong>
            <!-- Hidden input that stores the actual content submitted to the server -->
            <input id="x" type="hidden" name="body">

            <!-- Trix editor connected to hidden input -->
            <trix-editor input="x" class="trix-content"></trix-editor>
        </div>

        <!-- Submit button -->
        <button class="btn btn-success" type="submit">Submit</button>
    </form>
</div>

<!-- Trix editor JS -->
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

<script>
    // URL for uploading images via Trix editor
    var fileUploadURL = "{{ route('trix.upload') }}";
</script>

<!-- Custom JS to handle image attachments -->
<script src="{{ asset('js/attachments.js') }}"></script>

</body>
</html>
```

## 8) Create attachments.js

Create the file at:

public/js/attachments.js


And add:
```
(function() {
    // HOST variable holds the URL where the Trix editor will upload images
    var HOST = fileUploadURL;

    // Listen for the 'trix-attachment-add' event when a file is attached in Trix
    addEventListener("trix-attachment-add", function(event) {
        // Check if the attachment has a file
        if (event.attachment.file) {
            // Upload the file attachment
            uploadFileAttachment(event.attachment);
        }
    });

    // Function to handle uploading a Trix attachment
    function uploadFileAttachment(attachment) {
        // Call the uploadFile function, passing callbacks for progress and success
        uploadFile(
            attachment.file,
            setProgress,     // Callback to set upload progress
            setAttributes    // Callback to set attributes after successful upload
        );

        // Function to update upload progress on Trix editor
        function setProgress(progress) {
            attachment.setUploadProgress(progress); // Show progress bar
        }

        // Function to set attachment attributes (like URL) after successful upload
        function setAttributes(attributes) {
            attachment.setAttributes(attributes); // Update the Trix editor with uploaded file URL
        }
    }

    // Function to upload a file via XMLHttpRequest
    function uploadFile(file, progressCallback, successCallback) {
        var formData = createFormData(file); // Create form data with the file
        var xhr = new XMLHttpRequest();       // Create a new AJAX request

        xhr.open("POST", HOST, true); // POST request to the upload URL
        xhr.setRequestHeader('X-CSRF-TOKEN', getMeta('csrf-token')); // Set CSRF token header

        // Track upload progress
        xhr.upload.addEventListener("progress", function(event) {
            var progress = event.loaded / event.total * 100; // Calculate progress %
            progressCallback(progress);                     // Call the progress callback
        });

        // When upload is complete
        xhr.addEventListener("load", function(event) {
            var attributes = {
                url: xhr.responseText,                        // URL of uploaded image
                href: xhr.responseText + "?content-disposition=attachment" // Optional download link
            };
            successCallback(attributes);                     // Call success callback with image URL
        });

        xhr.send(formData); // Send the file data
    }

    // Function to create FormData object for the file
    function createFormData(file) {
        var data = new FormData();
        data.append("Content-Type", file.type); // Include the file type
        data.append("file", file);              // Append the file itself
        return data;
    }

    // Function to get the content of a meta tag (used for CSRF token)
    function getMeta(metaName) {
        const metas = document.getElementsByTagName('meta');
        for (let i = 0; i < metas.length; i++) {
            if (metas[i].getAttribute('name') === metaName) {
                return metas[i].getAttribute('content'); // Return the meta content
            }
        }
        return ''; // Return empty string if meta not found
    }
})();
```
## 9) Create posts.blade.php

File: resources/views/posts.blade.php
```
<h1>Saved Posts</h1>

<!-- Loop through all posts passed from the controller -->
@foreach($posts as $post)
    <!-- Display the post title with custom color and top margin -->
    <h2 style="color:#0d6efd; margin-top:1rem;">{{ $post->title }}</h2>

    <!-- Display the post body (HTML content from Trix editor) -->
    <div class="trix-content" style="line-height:1.6; font-size:1rem; color:#555;">
        {!! $post->body !!} 
    </div>

    <!-- Horizontal line to separate each post visually -->
    <hr style="border:1px solid #ccc;">
@endforeach

<!-- Custom styling for Trix content -->
<style>
.trix-content img {
    width: 15% !important;   /* Force the width of images inside Trix content */
    height: 300px !important; /* Force the height of images */
    display: block;           /* Display image as a block element */
    margin: 10px 0;           /* Vertical spacing above and below image */
    border-radius: 5px;       /* Rounded corners for images */
}

.trix-content p {
    margin-bottom: 1rem;      /* Add spacing between paragraphs */
}

.trix-content h1,
.trix-content h2,
.trix-content h3 {
    margin-top: 1.2rem;       /* Top margin for headings */
    margin-bottom: 0.5rem;    /* Bottom margin for headings */
}
</style>

```
## 10) Start Server & Test

Run:

```
php artisan serve
```

Then open:

```
http://localhost:8000/trix
```

## Output:
---

**Trix Page:**
```
http://localhost:8000/trix
```
<img width="1919" height="1091" alt="Screenshot 2025-12-12 181948" src="https://github.com/user-attachments/assets/f1fcf2e2-d330-4865-932c-fe0a91a5d0e1" />

---
**Post Page (Show):**
```
http://localhost:8000/posts
```
<img width="1918" height="1084" alt="Screenshot 2025-12-12 182224" src="https://github.com/user-attachments/assets/70669aba-566a-4675-84d0-2c32a44ac780" />

---

Now Your Project is Working Fine!

