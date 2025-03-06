@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa thương hiệu</h2>
    <form action="{{ route('brands.update', $brand->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" class="form-control" value="{{ $brand->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $brand->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
