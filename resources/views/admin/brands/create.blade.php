@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Thêm thương hiệu</h2>

    <form action="{{ route('admin.brands.store') }}" method="POST">
        @csrf

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Tên thương hiệu</th>
                        <td>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Nhập tên thương hiệu" value="{{ old('name') }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả</th>
                        <td>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                rows="3" placeholder="Nhập mô tả">{{ old('description') }}</textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
