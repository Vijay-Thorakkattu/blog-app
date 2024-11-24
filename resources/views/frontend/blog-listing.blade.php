<div class="container">
    <h1>Blog Posts</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @elseif (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div id="blog-posts">
        @if (session('posts') && count(session('posts')) > 0)
            @foreach (session('posts') as $post)
                <div class="blog-post">
                    <h3>{{ $post->name }}</h3>
                    <p><strong>{{ $post->author }}</strong> | {{ $post->date }}</p>
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->name }}" class="img-fluid">
                    <p>{{ $post->content }}</p>
                </div>
            @endforeach
        @else
            <p>No blog posts available.</p>
        @endif
    </div>
</div>
