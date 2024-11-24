@extends('admin.mainlayout.layout')

@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')

@section('content')
    <div class="row">
        <div class="container">
            <h1>Edit Vendor</h1>
    
            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    
            <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $vendor->name) }}" class="form-control" required>
                </div>
    
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $vendor->email) }}" class="form-control" required>
                </div>
    
                <div class="form-group">
                    <label for="password">Password (leave blank if not changing)</label>
                    <input type="password" id="password" name="password" value="{{ old('password', $vendor->password) }}" class="form-control">
                </div>
    
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>
    
                <button type="submit" class="btn btn-primary">Update Vendor</button>
            </form>
        </div>
    </div>
@endsection
