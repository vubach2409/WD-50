@extends('layouts.admin')

@section('title', 'Danh sách Voucher')

@section('content')
    <div class="container-fluid mt-4">
        <h2 class="text-primary">Danh sách Voucher</h2>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary mb-3">Thêm Voucher</a>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($vouchers->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Chưa có voucher nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">STT</th> 
                            <th class="text-center align-middle">Mã Voucher</th>
                            <th class="text-center align-middle">Loại</th>
                            <th class="text-center align-middle">Giá trị</th>
                            <th class="text-center align-middle">Giới hạn</th>
                            <th class="text-center align-middle">Trạng thái</th>
                            <th class="text-center align-middle">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $index => $voucher)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">{{ $voucher->code }}</td>
                                <td class="text-center align-middle">{{ $voucher->type }}</td>
                                <td class="text-center align-middle">{{ $voucher->value }} {{ $voucher->type == 'percent' ? '%' : 'VNĐ' }}</td>
                                <td class="text-center align-middle">{{ $voucher->usage_limit ?? '∞' }}</td>
                                <td class="text-center align-middle">
                                    @if ($voucher->is_active)
                                        <span class="badge bg-success text-light">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger text-light">Ngừng hoạt động</span> 
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-warning">Sửa</a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa voucher này?');">
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

        {{ $vouchers->links() }}
    </div>
@endsection
