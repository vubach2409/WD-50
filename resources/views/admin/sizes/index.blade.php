@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách Kích thước</h2>

    <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary mb-3">Thêm kích thước</a>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($sizes->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có kích thước nào.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Kích thước</th>
                        <th class="text-center align-middle">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sizes as $index => $size)
                    <tr>
                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                        <td class="text-center align-middle">{{ $size->name }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('admin.sizes.edit', $size->id) }}" class="btn btn-warning">Sửa</a>
                            <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
