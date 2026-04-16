<!DOCTYPE html>
<html>

<head>
    <title>Edit Post</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        body {
            background-color: #f1f3f5;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 25px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ced4da;
        }

        trix-editor {
            min-height: 200px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            margin-bottom: 20px;
        }

        .btn-update {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-update:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }

        .btn-back {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 20px;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-back:hover {
            background-color: #5c636a;
            transform: translateY(-2px);
        }

        ul {
            margin-bottom: 15px;
        }

        ul li {
            color: red;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Edit Post</h1>

        <!-- Back to Posts Button -->
        <a href="{{ route('trix.posts') }}" class="btn-back">← Back to Posts</a>

        <!-- Validation Errors -->
        @if($errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('trix.update', $post->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="title" value="{{ $post->title }}" placeholder="Post Title">

            <input id="body" type="hidden" name="body" value="{{ $post->body }}">
            <trix-editor input="body"></trix-editor>

            <button type="submit" class="btn-update">Update Post</button>
        </form>
    </div>

    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
</body>

</html>