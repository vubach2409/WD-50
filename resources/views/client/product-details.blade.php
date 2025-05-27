@extends('layouts.user')

@section('title', $product->name)

@section('content')
<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Ảnh sản phẩm --}}
            <div class="col-md-6">
                <div id="productCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ asset('storage/' . $product->image) }}" class="d-block w-100 view-image" alt="{{ $product->name }}" style="cursor: zoom-in;">
                        </div>
                        @foreach ($product->variants as $variant)
                            @if ($variant->image)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $variant->image) }}" class="d-block w-100 view-image" alt="Ảnh biến thể" style="cursor: zoom-in;">
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </div>

            {{-- Thông tin sản phẩm --}}
            <div class="col-md-6">
                <h1>{{ $product->name }}</h1>

                {{-- Đánh giá --}}
                <div class="mb-2 text-warning fs-5">
                    @php $rating = $product->average_rating ?? 0; @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= floor($rating) ? 'bi-star-fill' : ($i - $rating <= 0.5 ? 'bi-star-half' : 'bi-star') }}"></i>
                    @endfor
                    <small class="text-muted ms-2">({{ $product->reviews_count }} đánh giá)</small>
                </div>

                {{-- Giá --}}
                <h4>
                    @if ($minPrice === $maxPrice)
                        <strong class="text-danger fw-bold" id="defaultPrice">
                            {{ number_format($minPrice, 0, ',', ',') }} ₫
                        </strong>
                    @else
                        <strong class="text-danger fw-bold" id="defaultPrice">
                            {{ number_format($minPrice, 0, ',', ',') }} ₫ - {{ number_format($maxPrice, 0, ',', ',') }} ₫
                        </strong>
                        <strong class="text-danger fw-bold d-none" id="variantPrice"></strong>
                    @endif
                </h4>
                <p>{{ $product->short_description }}</p>
                <p><strong>SKU:</strong> <span id="variantSku">{{ $product->sku ?? 'Chưa chọn' }}</span></p>
                <p><strong>Kho:</strong> <span id="stockStatus">Chưa chọn</span></p>

                {{-- Chọn màu & size --}}
                @if ($product->variants->count() > 0)
                    <div class="row">
                        {{-- Màu --}}
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold d-block">Chọn màu:</label>
                            <div id="colorOptions" class="d-flex flex-wrap gap-2">
                                @foreach ($product->variants->unique('color_id') as $variant)
                                    <button type="button" class="btn color-swatch border rounded-circle p-0"
                                        data-color-id="{{ $variant->color_id }}"
                                        style="width: 36px; height: 36px; background-color: {{ $variant->color->code }};"
                                        title="{{ $variant->color->name }}"></button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Size --}}
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Chọn size:</label>
                            <select id="variantSize" class="form-select" disabled>
                                <option value="" class="text-danger">Vui lòng chọn màu sắc trước!</option>
                            </select>
                        </div>
                    </div>

                    {{-- Form thêm giỏ hàng --}}
                    @if (!Auth::check() || (Auth::check() && Auth::user()->role !== 'nhanvien'))
                        <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="variant_id" id="variant_id" value="">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Số lượng:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" disabled>
                            </div>
                            <button class="btn btn-primary w-100" type="submit" id="addToCartBtn" disabled>
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-danger">Sản phẩm này hiện không còn hàng.</div>
                @endif
            </div>
        </div>

        {{-- Chi tiết sản phẩm --}}
        <div class="mt-5">
            <h4>Chi tiết sản phẩm</h4>
            <div class="border rounded p-3 bg-light">
                {!! nl2br(e($product->description ?? 'Chưa có mô tả chi tiết.')) !!}
            </div>
        </div>

        {{-- Đánh giá sản phẩm --}}
        <div class="mt-5">
            <h4>Đánh giá sản phẩm</h4>
            @auth
                {{-- Bạn có thể thêm lại form đánh giá ở đây --}}
            @else
                <div class="alert alert-warning">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</div>
            @endauth

            <div class="border rounded p-3">
                @forelse ($product->feedbacks as $feedback)
                    <div class="mb-3">
                        <strong>{{ $feedback->user->name }}</strong>
                        <small class="text-muted">- {{ $feedback->created_at->diffForHumans() }}</small>

                        {{-- Biến thể --}}
                        @if ($feedback->variation)
                            <div class="text-dark mb-1">
                                Biến thể:
                                {{ $feedback->variation->variation_name ?? 'Mặc định' }}
                                @if ($feedback->variation->color)
                                    - Màu:
                                    <span style="display:inline-block; width:16px; height:16px; background-color: {{ $feedback->variation->color->code }}; border:1px solid #ccc; border-radius:4px; vertical-align:middle;"></span>
                                @endif
                                @if ($feedback->variation->size)
                                    - Size: {{ $feedback->variation->size->name }}
                                @endif
                            </div>
                        @endif

                        <div class="text-warning mb-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $feedback->star ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>

                        <p class="mb-1">{{ $feedback->content }}</p>
                        <hr>
                    </div>
                @empty
                    <p class="text-muted">Chưa có đánh giá nào.</p>
                @endforelse
            </div>
        </div>

        {{-- Sản phẩm liên quan --}}
        <div class="mt-5 popular-product">
            <h4 class="mb-4 text-center">Sản phẩm liên quan</h4>
            <div class="row justify-content-center">
                @forelse ($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="product-item position-relative overflow-hidden">
                            <a href="{{ route('product.details', $related->id) }}" class="text-decoration-none">
                                <div class="product-img">
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="img-fluid rounded-3">
                                </div>
                                <div class="product-info">
                                    <h6 class="text-truncate">{{ $related->name }}</h6>
                                    <span class="text-danger price fw-bold">
                                        @if ($related->minPrice === $related->maxPrice)
                                            {{ number_format($related->minPrice, 0, ',', ',') }} ₫
                                        @else
                                            {{ number_format($related->minPrice, 0, ',', ',') }} ₫ - {{ number_format($related->maxPrice, 0, ',', ',') }} ₫
                                        @endif
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Không có sản phẩm liên quan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal zoom ảnh --}}
<div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="zoomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0 text-center">
        <img src="" alt="Ảnh lớn" id="zoomImage" class="img-fluid rounded">
      </div>
      <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
  </div>
</div>
@php
$variants = $product->variants->map(function($v) {
    return [
        'id' => $v->id,
        'color_id' => $v->color_id,
        'color_name' => $v->color->name,
        'size_id' => $v->size_id,
        'size_name' => $v->size ? $v->size->name : null,
        'price' => $v->price,
        'stock' => $v->stock,
        'sku' => $v->sku,
        'image' => $v->image ? asset('storage/' . $v->image) : null,
    ];
});
@endphp

<script>
    const variants = @json($variants);
    console.log(variants);
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorButtons = document.querySelectorAll('#colorOptions .color-swatch');
    const sizeSelect = document.getElementById('variantSize');
    const variantIdInput = document.getElementById('variant_id');
    const quantityInput = document.getElementById('quantity');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const variantPrice = document.getElementById('variantPrice');
    const defaultPrice = document.getElementById('defaultPrice');
    const variantSku = document.getElementById('variantSku');
    const stockStatus = document.getElementById('stockStatus');

    let selectedColorId = null;

    function resetSizeOptions() {
        sizeSelect.innerHTML = '<option value="">Chọn size</option>';
        sizeSelect.disabled = true;
        variantIdInput.value = '';
        quantityInput.disabled = true;
        addToCartBtn.disabled = true;
        variantPrice.classList.add('d-none');
        defaultPrice.classList.remove('d-none');
        variantSku.innerText = 'Chưa chọn';
        stockStatus.innerText = 'Chưa chọn';
    }

    function populateSizes(colorId) {
        const sizeSelect = document.getElementById('variantSize');
        sizeSelect.innerHTML = '<option value="">-- Chọn size --</option>';

        const sizes = variants.filter(v => v.color_id === colorId && v.size_id !== null);
        const uniqueSizes = [...new Map(sizes.map(v => [v.size_id, v])).values()];

        // Sắp xếp theo size_id hoặc size_order
        uniqueSizes.sort((a, b) => a.size_id - b.size_id);

        uniqueSizes.forEach(variant => {
            const option = document.createElement('option');
            const isOutOfStock = variant.stock <= 0;

            option.value = variant.id;
            option.textContent = isOutOfStock
                ? `${variant.size_name} (Hết hàng)`
                : variant.size_name;

            if (isOutOfStock) {
                option.disabled = true;
                option.style.fontStyle = 'italic';
                option.classList.add('text-muted');
            }

            sizeSelect.appendChild(option);
        });

        sizeSelect.disabled = false;
    }

    colorButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Đánh dấu màu đang chọn
            colorButtons.forEach(b => b.classList.remove('border-primary', 'border-3'));
            button.classList.add('border-primary', 'border-3');
            selectedColorId = parseInt(button.dataset.colorId);

            // Giữ nguyên thông tin biến thể cũ (nếu có), chỉ reset size
            sizeSelect.innerHTML = '<option value="">-- Chọn size --</option>';
            populateSizes(selectedColorId);
        });
    });

    sizeSelect.addEventListener('change', () => {
        const selectedVariantId = parseInt(sizeSelect.value);
        const variant = variants.find(v => v.id === selectedVariantId && v.color_id === selectedColorId);

        if (variant) {
            variantIdInput.value = variant.id;
            quantityInput.disabled = false;
            addToCartBtn.disabled = false;
            variantSku.innerText = variant.sku || 'Không có';
            stockStatus.innerText = variant.stock > 0 ? `${variant.stock} sản phẩm` : 'Hết hàng';

            variantPrice.textContent = new Intl.NumberFormat('en-US').format(variant.price) + ' ₫';
            variantPrice.classList.remove('d-none');
            defaultPrice.classList.add('d-none');
        } else {
            variantIdInput.value = '';
            quantityInput.disabled = true;
            addToCartBtn.disabled = true;
            variantSku.innerText = 'Chưa chọn';
            stockStatus.innerText = 'Chưa chọn';
            variantPrice.classList.add('d-none');
            defaultPrice.classList.remove('d-none');
        }
    });

    // Zoom ảnh
    const zoomModal = new bootstrap.Modal(document.getElementById('zoomModal'));
    const zoomImage = document.getElementById('zoomImage');

    document.querySelectorAll('.view-image').forEach(img => {
        img.addEventListener('click', () => {
            zoomImage.src = img.src;
            zoomModal.show();
        });
    });
});
</script>
@endpush

@endsection
