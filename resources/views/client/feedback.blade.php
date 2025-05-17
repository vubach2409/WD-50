@extends('layouts.user')

@section('title', 'Đánh giá đơn hàng')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Đánh giá đơn hàng #{{ $order->id }}</h3>

        <form action="{{ route('orders.feedback.submit', $order->id) }}" method="POST">
            @csrf

            @foreach ($products as $item)
                <div class="card mb-3">
                    <div class="card-body d-flex">
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                            class="me-3 rounded" style="width: 100px; height: 100px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h5>{{ $item->product->name }}</h5>

                            <!-- Star Rating -->
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label>
                                        <input type="radio" name="feedbacks[{{ $item->product->id }}][star]"
                                            value="{{ $i }}" class="d-none">
                                        <i class="bi bi-star-fill text-muted star-icon"
                                            style="font-size: 1.5rem; cursor: pointer;"></i>
                                    </label>
                                @endfor
                            </div>

                            <!-- Comment -->
                            <textarea name="feedbacks[{{ $item->product->id }}][content]" rows="2" class="form-control"
                                placeholder="Cảm nhận của bạn về sản phẩm này..." required></textarea>
                        </div>
                    </div>
                </div>
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
        // Highlight selected stars
        document.querySelectorAll('.card').forEach(card => {
            const stars = card.querySelectorAll('.star-icon');
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    stars.forEach((s, i) => {
                        s.classList.toggle('text-warning', i <= index);
                        s.classList.toggle('text-muted', i > index);
                        s.previousElementSibling.checked = i === index; // set the radio
                    });
                });
            });
        });
    </script>
@endpush