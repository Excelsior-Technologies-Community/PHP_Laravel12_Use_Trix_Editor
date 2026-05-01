<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background-color: #f4f7f6;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .posts-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .post-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 350px;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .post-title {
        color: #0d6efd;
        margin-bottom: 10px;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .post-stats {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
    }

    .trix-content {
        flex-grow: 1;
        max-height: 200px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .trix-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .status {
        font-weight: 500;
        color: #555;
        font-size: 0.9rem;
    }

    .buttons {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

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
        font-size: 0.85rem;
    }

    .btn-edit {
        background-color: #0d6efd;
        color: white;
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
    }

    .btn-toggle {
        background-color: #198754;
        color: white;
    }

    .search-section {
        max-width: 600px;
        margin: 0 auto 40px auto;
    }

    .pagination-wrapper {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }
</style>

<body>

    <div class="container py-5">
        <h1 style="text-align:center; margin-bottom:2rem; color:#0d6efd; font-weight: 700;">Saved Posts</h1>

        <div class="search-section">
            <form action="{{ route('trix.posts') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by title or content..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('trix.posts') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center mb-4">{{ session('success') }}</div>
        @endif

        <div class="posts-container">
            @forelse($posts as $post)
                <div class="post-card">
                    <h2 class="post-title">{{ $post->title }}</h2>

                    <div class="post-stats">
                        <span>⏱ {{ $post->read_time }} min read</span>
                        <span>📝 {{ $post->word_count }} words</span>
                    </div>

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
                            <button type="submit"
                                class="btn-toggle">{{ $post->status ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center w-100 mt-5">
                    <h4 class="text-muted">No posts found.</h4>
                </div>
            @endforelse
        </div>

        <div class="pagination-wrapper">
            {{ $posts->appends(['search' => request('search')])->links() }}
        </div>
    </div>

</body>

</html>