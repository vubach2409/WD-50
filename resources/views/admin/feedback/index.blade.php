@extends('layouts.admin')

@section('title', 'Quản lý đánh giá theo biến thể sản phẩm')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary">Danh sách đánh giá</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- <form method="GET" action="{{ route('admin.feedbacks.index') }}" class="mb-3">
            <select name="variant_id" class="form-select w-auto d-inline" onchange="this.form.submit()">
                <option value="">-- Tất cả biến thể --</option>
                @foreach ($variants as $variant)
                    <option value="{{ $variant->id }}" {{ (int) $variantId === $variant->id ? 'selected' : '' }}>
                        {{ $variant->product->name ?? '[SP đã xoá]' }} - {{ $variant->name }}
                    </option>
                @endforeach
            </select>
        </form> --}}

        @if ($feedbacks->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-1"></i> Chưa có đánh giá nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Sản phẩm - Biến thể</th>
                            <th>Người dùng</th>
                            <th>Rating</th>
                            <th>Bình luận</th>
                            <th>Ngày</th>
                            <th>Ẩn/Hiện</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $index => $fb)
                            <tr>
                                <td>{{ $feedbacks->firstItem() + $index }}</td>
                                <td>
                                    @if ($fb->variation && $fb->variation->product)
                                        {{ $fb->variation->product->name }} - {{ $fb->variation->variation_name }}
                                        <br>
                                        <small>
                                            <span
                                                style="display:inline-block; width:16px; height:16px; background-color: {{ $fb->variation->color->name ?? 'Chưa xác định' }}; border:1px solid #ccc; border-radius:4px; vertical-align:middle;"></span>
                                            -
                                            {{ $fb->variation->size->name ?? 'Chưa xác định' }}
                                        </small>
                                    @else
                                        [Biến thể hoặc sản phẩm không tồn tại]
                                    @endif
                                </td>
                                <td>{{ $fb->user->name ?? '[Người dùng đã xoá]' }}</td>
                                <td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $fb->star ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
                                    @endfor
                                </td>
                                <td>{{ $fb->content }}</td>
                                <td>{{ $fb->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $fb->is_hidden ? 'Đang ẩn' : 'Hiện' }}</td>
                                <td>
                                    <form action="{{ route('admin.feedbacks.destroy', $fb->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Xác nhận xóa đánh giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                    <form action="{{ route('admin.feedbacks.toggleHide', $fb->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Xác nhận ẩn/hiện đánh giá này?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            {{ $fb->is_hidden ? 'Hiện' : 'Ẩn' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $feedbacks->links() }}
        @endif
    </div>
@endsection
