@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm thương hiệu mới</h2>
    <form action="{{ route('brands.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên thương hiệu</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</div>
@endsection
