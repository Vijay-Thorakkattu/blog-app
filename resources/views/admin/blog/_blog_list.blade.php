@forelse ($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card">
                @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->name }}" style="width: 300px; height: 200px;">
                @else
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="No image available">
                @endif
                <div class="card-body">
                    <div>
                        <label for="name"><strong>Name:</strong></label>
                        <span id="name">{{ $post->name }}</span>
                    </div>
                    <p class="card-text">{{ Str::limit(strip_tags($post->content), 50) }}</p>
                    <div>
                        <label for="author"><strong>Author:</strong></label>
                        <span id="author">{{ $post->author }}</span>
                    </div>
                    <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $post->id }}">Delete</button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning">
                No results found for your search.
            </div>
        </div>
@endforelse
