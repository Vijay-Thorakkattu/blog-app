@extends('admin.mainlayout.layout')

@section('title', 'Blogs')
@section('page-title', 'Blog List')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Blogs</h4>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">Create</a>
        </div>
        <div class="mb-4">
            <input type="text" id="search" class="form-control" placeholder="Search blogs...">
        </div>

        <div  id="blog-list" class="row mt-3">
            @forelse ($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->name }}"
                                style="width: 300px; height: 200px;">
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
                        No blogs found.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end mt-4">
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('click', '.delete-btn', function() {
            const postId = $(this).data('id');
            const url = `{{ url('admin/blog') }}/${postId}`;

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            url: url,
                            method: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            }
                        })
                        .done(function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });

                            $(`button[data-id="${postId}"]`).closest('tr').remove();
                        })
                        .fail(function(xhr, status, error) {
                            Swal.fire({
                                title: "Error!",
                                text: "Failed to delete the blog. Please try again.",
                                icon: "error",
                                confirmButtonColor: "#d33"
                            });
                        });
                }
            });
        });
    </script>

<script>
    $('#search').on('input', function() {
        let query = $(this).val();
        $.ajax({
            url: '{{ route('admin.blog.search') }}',
            method: 'GET',
            data: { query: query },
        })
        .done(function(response) {
            $('#blog-list').html(response);
        })
        .fail(function(xhr, status, error) {
            Swal.fire({
                title: "Error!",
                text: "Something went wrong while fetching the posts. Please try again.",
                icon: "error",
                confirmButtonColor: "#d33"
            });
        });
    });
</script>
    @endsection
