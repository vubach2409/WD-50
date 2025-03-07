@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm kích thước</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sizes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Kích thước</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
