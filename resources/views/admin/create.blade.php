@extends('admin.mainlayout.layout')

@section('title', 'New Page')
@section('page-title', 'New Page Title')
@section('content')
    <div class="row">
        <div class="container">
            <h1>Create Vendor</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
           

            <form action="{{ route('vendors.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>
    
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
    
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
    
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
    
                <button type="submit" class="btn btn-primary">Create Vendor</button>
                <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection