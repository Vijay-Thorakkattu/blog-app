@extends('admin.mainlayout.layout')

@section('title', 'Edit Blog')
@section('page-title', 'Edit Blog')
@section('content')
    <div class="row">
        <div class="container">
            <h4>Edit Blog</h4>
            <form id="blogForm" action="{{ route('admin.blog.update', $post->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="blog_name">Name<span class="text-danger">*</span></label>
                            <input type="text" id="blog_name" name="blog_name" placeholder="Enter the blog name"
                                value="{{ old('name', $post->name) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="blog_date">Date<span class="text-danger">*</span></label>
                            <input type="date" id="blog_date" name="blog_date" placeholder="Enter the blog Date"
                                value="{{ old('blog_date', $post->date) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="author">Author<span class="text-danger">*</span></label>
                            <input type="text" id="blog_author" name="blog_author" placeholder="Enter the author Name"
                                value="{{ old('blog_author', $post->author) }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Image<span class="text-danger">*</span></label>
                            <input type="file" id="blog_image" name="blog_image" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group" style="position: relative;">
                            @if ($post->image)
                                <img id="image_preview" src="{{ Storage::url($post->image) }}" alt="Image Preview"
                                    style="max-width: 300px; height: auto;" />
                                <button type="button" id="remove_image"
                                    style="position: absolute; top: -5px; right: -5px; background: rgba(0, 0, 0, 0.5); color: white; border: none; border-radius: 50%; padding: 5px; font-size: 16px;">
                                    ×
                                </button>
                            @else
                                <img id="image_preview" src="#" alt="Image Preview"
                                    style="max-width: 300px; height: auto; display: none;" />
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="content">Content<span class="text-danger">*</span></label>
                            <textarea id="blog_content" name="blog_content" placeholder="Enter the Content" class="form-control">{{ old('blog_content', $post->content) }}</textarea>
                            <span id="content_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary ms-3">Update</button>
                </div>

            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let editor;
        ClassicEditor
            .create(document.querySelector('#blog_content'), {
                toolbar: [
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'
                ]
            })
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        $(document).ready(function() {
            var today = new Date();
            var day = today.getDate();
            var month = today.getMonth() + 1;
            var year = today.getFullYear();

            if (day < 10) {
                day = '0' + day;
            }
            if (month < 10) {
                month = '0' + month;
            }

            var minDate = year + '-' + month + '-' + day;

            $('#blog_date').attr('min', minDate);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#blog_image').on('change', function(event) {
                var fileInput = $(this)[0];
                var previewImage = $('#image_preview')[0];
                var removeButton = $('#remove_image');

                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $(previewImage).attr('src', e.target.result);
                        $(previewImage).show();
                        $(removeButton).show();
                    };

                    reader.readAsDataURL(fileInput.files[0]);
                }
            });

            $('#remove_image').on('click', function() {
                $('#blog_image').val('');
                $('#image_preview').hide();
                $(this).hide();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#blogForm').on('submit', function(event) {
                event.preventDefault();
                const content = editor.getData();
                $('#blog_content').val(content);
                var formData = new FormData(this);

                $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                    })
                    .done(function(response) {
                        toastr.success(response.message);
                        window.location.href = '{{ route('admin.blog.index') }}';
                    })
                    .fail(function(response) {
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;

                            if (errors.blog_name) {
                                toastr.error(errors.blog_name[0]);
                            }

                            if (errors.blog_date) {
                                toastr.error(errors.blog_date[0]);
                            }

                            if (errors.blog_author) {
                                toastr.error(errors.blog_author[0]);
                            }

                            if (errors.blog_content) {
                                toastr.error(errors.blog_content[0]);
                            }

                            if (errors.blog_image) {
                                toastr.error(errors.blog_image[0]);
                            }
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    });
            });
        });
    </script>
@endsection
