<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JWT Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-5" id="login_container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Login with JWT</h2>
            <a href="{{ route('login') }}" class="btn btn-primary">Admin Login</a>
        </div>

        <form id="login-form">
            <div id="message" class="mt-3"></div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

    </div>

    <div id="blog-listing"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                const email = $('#email').val();
                const password = $('#password').val();

                $.ajax({
                        url: '/api/login',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            email: email,
                            password: password
                        })
                    })
                    .done(function(response) {
                        localStorage.setItem('token', response.token);
                        $.ajax({
                                url: '/api/blog-listing',
                                method: 'GET',
                                headers: {
                                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                                }
                            })
                            .done(function(response) {

                                $('#login_container').hide();
                                if (response.success && response.data.length > 0) {
                                    let htmlContent = '<div class="blog-listing-container">';
                                    response.data.forEach(post => {
                                        htmlContent += `
                                    <div class="blog-post">
                                        <h3>${post.name}</h3>
                                        <p><strong>Author:</strong> ${post.author}</p>
                                        <p><strong>Date:</strong> ${post.date}</p>
                                        <div class="content">${post.content}</div>
                                        <img src="${post.image_url}" alt="${post.name}" class="img-fluid">
                                    </div>
                                `;
                                    });

                                    htmlContent += '</div>';
                                    $('#blog-listing').html(htmlContent);
                                } else {
                                    $('#blog-listing').html('<p>No blog posts found.</p>');
                                }
                            })
                            .fail(function(xhr, status, error) {
                                console.log('Error:', error);
                                $('#blog-listing').html(
                                    '<p>Error fetching blog posts. Please try again later.</p>');
                            });
                    })
                    .fail(function(xhr) {
                        $('.login_container').show();
                        const errorMessage = xhr.responseJSON?.error || 'An error occurred';
                        $('#message').html(
                            `<div class="alert alert-danger">${errorMessage}</div>`
                        );
                    });
            });
        });
    </script>

</body>

</html>
