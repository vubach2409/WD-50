@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách Thương hiệu</h2>

    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary mb-3">Thêm thương hiệu</a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($brands->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có thương hiệu nào.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Tên thương hiệu</th>
                        <th class="text-center align-middle">Mô tả</th> 
                        <th class="text-center align-middle">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brands as $index => $brand)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td> 
                        <td class="text-center align-middle">{{ $brand->name }}</td> 
                        <td class="text-center align-middle">{{ $brand->description }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
