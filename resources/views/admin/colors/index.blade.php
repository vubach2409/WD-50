@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách Màu sắc</h2>

    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary mb-3">Thêm màu sắc</a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($colors->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có màu sắc nào.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Màu</th>
                        <th class="text-center align-middle">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colors as $index => $color)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                        <td class="text-center align-middle">
                            <div class="d-flex flex-column align-items-center">
                                <div style="width: 40px; height: 40px; background-color: {{ $color->code }}; border: 1px solid #ccc; border-radius: 4px;"></div>
                                <small class="mt-1">{{ $color->code }}</small>
                            </div>
                        </td>                        
                        <td class="text-center align-middle">
                            <a href="{{ route('admin.colors.edit', $color->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('admin.colors.destroy', $color->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
