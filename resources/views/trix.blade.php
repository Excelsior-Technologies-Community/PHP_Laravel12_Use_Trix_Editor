<!DOCTYPE html>
<html>
<head>
    <title>Trix Editor in Laravel 12</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        .trix-content img {
            width: 500px;
            height: 300px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3>Trix Editor with Image Upload</h3>

    <form action="{{ route('trix.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <strong>Title:</strong>
            <input type="text" name="title" class="form-control" placeholder="Title">
        </div>

        <div class="mb-3">
            <strong>Body:</strong>
            <input id="x" type="hidden" name="body">
            <trix-editor input="x" class="trix-content"></trix-editor>
        </div>

        <button class="btn btn-success" type="submit">Submit</button>
    </form>
</div>

<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
    var fileUploadURL = "{{ route('trix.upload') }}";
</script>
<script src="{{ asset('js/attachments.js') }}"></script>

</body>
</html>
