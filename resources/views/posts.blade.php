<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    /* Container for posts */
    .posts-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Individual post card */
    .post-card {
        background-color: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 350px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    /* Post title */
    .post-title {
        color: #0d6efd;
        margin-bottom: 15px;
        font-size: 1.4rem;
        font-weight: 600;
    }

    /* Trix content styling */
    .trix-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 10px 0;
    }

    .trix-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
        color: #333;
    }

    .trix-content h1,
    .trix-content h2,
    .trix-content h3 {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #0d6efd;
    }

    /* Status styling */
    .status {
        font-weight: 500;
        margin-top: 10px;
        color: #555;
    }

    /* Buttons container */
    .buttons {
        margin-top: 15px;
    }

    /* Button styles */
    .buttons a,
    .buttons button {
        display: inline-block;
        margin-right: 5px;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: background 0.2s, transform 0.2s;
    }

    /* Edit button */
    .btn-edit {
        background-color: #0d6efd;
        color: white;
    }

    .btn-edit:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
    }

    /* Delete button */
    .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background-color: #bb2d3b;
        transform: translateY(-2px);
    }

    /* Toggle button */
    .btn-toggle {
        background-color: #198754;
        color: white;
    }

    .btn-toggle:hover {
        background-color: #146c43;
        transform: translateY(-2px);
    }
</style>

<body>

    <h1 style="text-align:center; margin-bottom:2rem; color:#0d6efd;">Saved Posts</h1>

    @if(session('success'))
        <p style="color:green; text-align:center; font-weight:bold;">{{ session('success') }}</p>
    @endif

    <div class="posts-container" style="display:flex; flex-wrap:wrap; gap:20px; justify-content:center;">

        @foreach($posts as $post)
            <div class="post-card">
                <h2 class="post-title">{{ $post->title }}</h2>

                <div class="trix-content">
                    {!! $post->body !!}
                </div>

                <p class="status">Status: <strong>{{ $post->status ? 'Active' : 'Inactive' }}</strong></p>

                <div class="buttons">
                    <a href="{{ route('trix.edit', $post->id) }}" class="btn-edit">Edit</a>

                    <form action="{{ route('trix.destroy', $post->id) }}" method="POST" style="display:inline;"
                        onsubmit="return confirm('Are you sure you want to delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                    <form action="{{ route('trix.toggle', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-toggle">{{ $post->status ? 'Deactivate' : 'Activate' }}</button>
                    </form>
                </div>
            </div>
        @endforeach

    </div>


</body>

</html>