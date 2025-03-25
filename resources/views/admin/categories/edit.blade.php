@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Chỉnh sửa danh mục</h2>
    <form action="{{ route('categories.update', $category->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn cập nhật danh mục này?');">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
