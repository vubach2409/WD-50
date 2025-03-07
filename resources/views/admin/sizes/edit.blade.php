@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa kích thước</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sizes.update', $size->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Kích thước</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $size->name) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
