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
