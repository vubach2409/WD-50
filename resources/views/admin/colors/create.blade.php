@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm Màu sắc</h2>
    
    <form action="{{ route('admin.colors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Màu</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
