@extends('admin.mainlayout.layout')

@section('title', 'New Page')
@section('page-title', 'New Page Title')
@section('content')
    <div class="row">
        <div class="container">
            <h1>Vendors</h1>
    
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="mb-3">
                <a href="{{ route('vendors.create') }}" class="btn btn-primary">Add Vendor</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>
                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $vendors->links() }}
        </div>
    </div>
@endsection