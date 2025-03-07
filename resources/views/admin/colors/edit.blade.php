@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa Màu sắc</h2>
    
    <form action="{{ route('admin.colors.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Màu</label>
            <input type="text" name="name" class="form-control" value="{{ $color->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
