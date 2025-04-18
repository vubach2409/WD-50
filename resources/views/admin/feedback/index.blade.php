@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid mt-4">
    <h2 class="text-primary mb-4">Danh sách đánh giá</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($feedbacks->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Chưa có đánh giá nào.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th class="text-center align-middle">STT</th>
                        <th class="text-center align-middle">Sản phẩm</th>
                        <th class="text-center align-middle">Người dùng</th>
                        <th class="text-center align-middle">Rating</th>
                        <th class="text-center align-middle">Bình luận</th>
                        <th class="text-center align-middle">Ngày</th>
                        <th class="text-center align-middle">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $index => $fb)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $fb->product->name ?? '[SP đã xoá]' }}</td>
                            <td class="align-middle">{{ $fb->user->name ?? '[Người dùng đã xoá]' }}</td>
                            <td class="align-middle">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $fb->star ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
                                @endfor
                            </td>
                            <td class="align-middle">{{ $fb->content }}</td>
                            <td class="align-middle">{{ $fb->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('admin.feedbacks.destroy', $fb->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa đánh giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mr-1">Xóa</button> <!-- me-2 tạo khoảng cách giữa các nút -->
                                    </form>

                                    <form action="{{ route('admin.feedbacks.toggleHide', $fb->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận ẩn/hiện đánh giá này?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            {{ $fb->is_hidden ? 'Hiện' : 'Ẩn' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{ $feedbacks->links() }}
</div>
@endsection
