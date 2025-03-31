@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Chỉnh sửa danh mục</h2>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" 
          onsubmit="return confirm('Bạn có chắc chắn muốn cập nhật danh mục này?');">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Tên danh mục</th>
                        <td>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $category->name) }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả</th>
                        <td>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                rows="3"  placeholder="Nhập mô tả">{{ old('description', $category->description) }}</textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
