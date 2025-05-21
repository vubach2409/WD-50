@extends('layouts.user')

@section('title', 'Đánh giá đơn hàng')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Đánh giá đơn hàng #{{ $order->id }}</h3>

        <form action="{{ route('orders.feedback.submit', $order->id) }}" method="POST">
            @csrf

            @foreach ($products as $item)
                @php
                    $variation = $item->variant ?? null;
                    $product = $item->product ?? null;

                    $variationId = $item->variant_id;

                    $oldStar = $variationId ? old("feedbacks.{$variationId}.star", null) : null;
                    $oldContent = $variationId ? old("feedbacks.{$variationId}.content", '') : '';
                @endphp

                @if ($variation && $product)
                    <div class="card mb-3">
                        <div class="card-body d-flex">
                            @if ($item->variant_image)
                                <img src="{{ asset('storage/' . $item->variant_image) }}" alt="{{ $product->name }}"
                                    class="me-3 rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="me-3 rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif

                            <div class="flex-grow-1">
                                <h5>{{ $product->name }}</h5>
                                <p class="text-muted mb-1">Biến thể: {{ $variation->variation_name ?? 'Mặc định' }}</p>
                                <p class="text-muted mb-1">Màu: <span
                                        style="display:inline-block; width:16px; height:16px; background-color: {{ $item->color_name }}; border:1px solid #ccc; border-radius:4px; vertical-align:middle;"></span>
                                </p>

                                <p class="text-muted">Size: {{ $item->size_name ?? 'Không có' }}</p>

                                <div class="mb-2">
                                    {{-- Ẩn input radio để đảm bảo required --}}
                                    <input type="radio" name="feedbacks[{{ $variationId }}][star]" value=""
                                        style="display:none;" required>

                                    @for ($i = 1; $i <= 5; $i++)
                                        <label>
                                            <input type="radio" name="feedbacks[{{ $variationId }}][star]"
                                                value="{{ $i }}" class="d-none"
                                                {{ $oldStar == $i ? 'checked' : '' }}>
                                            <i class="bi bi-star-fill {{ $oldStar >= $i ? 'text-warning' : 'text-muted' }} star-icon"
                                                style="font-size: 1.5rem; cursor: pointer;"></i>
                                        </label>
                                    @endfor
                                </div>

                                <textarea name="feedbacks[{{ $variationId }}][content]" rows="2" class="form-control"
                                    placeholder="Cảm nhận của bạn về biến thể này..." required>{{ $oldContent }}</textarea>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Dữ liệu biến thể hoặc sản phẩm không hợp lệ.
                    </div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.card').forEach(card => {
            const stars = card.querySelectorAll('.star-icon');
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    stars.forEach((s, i) => {
                        s.classList.toggle('text-warning', i <= index);
                        s.classList.toggle('text-muted', i > index);
                        s.previousElementSibling.checked = i === index;
                    });
                });
            });
        });
    </script>
@endpush
