@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
    <div class="container">
        <h2 class="mb-4">Danh sách đánh giá</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Người dùng</th>
                    <th>Rating</th>
                    <th>Bình luận</th>
                    <th>Ngày</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($feedbacks as $fb)
                    <tr>
                        <td>{{ $fb->id }}</td>
                        <td>{{ $fb->product->name ?? '[SP đã xoá]' }}</td>
                        <td>{{ $fb->user->name ?? '[Người dùng đã xoá]' }}</td>
                        <td>
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $fb->star ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
                            @endfor
                        </td>
                        <td>{{ $fb->content }}</td>
                        <td>{{ $fb->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.feedbacks.destroy', $fb->id) }}" method="POST"
                                onsubmit="return confirm('Xác nhận xóa đánh giá này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Không có đánh giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $feedbacks->links() }}
    </div>
@endsection
