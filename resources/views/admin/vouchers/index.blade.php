@extends('layouts.admin')

@section('title', 'Danh sách Voucher')

@section('content')
    <div class="container mt-4">
        <h2>Danh sách Voucher</h2>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success mb-3">+ Thêm Voucher</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Giới hạn</th>
                    <th>Đã dùng</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->code }}</td>
                        <td>{{ $voucher->type }}</td>
                        <td>{{ $voucher->value }} {{ $voucher->type == 'percent' ? '%' : 'VNĐ' }}</td>
                        <td>{{ $voucher->usage_limit ?? '∞' }}</td>
                        <td>{{ $voucher->used }}</td>
                        <td>
                            {{ $voucher->starts_at ? $voucher->starts_at->format('d/m/Y') : '-' }} -
                            {{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            @if ($voucher->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-warning">Tắt</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Xóa voucher này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $vouchers->links() }}
    </div>
@endsection
