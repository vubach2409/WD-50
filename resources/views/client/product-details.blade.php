@extends('layouts.user')

@section('title', $product->name)

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
                    role="alert" style="z-index: 9999;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow"
                    role="alert" style="z-index: 9999;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div id="productCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            {{-- Ảnh chính --}}
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $product->image) }}" class="d-block w-100"
                                    alt="{{ $product->name }}">
                            </div>
                            {{-- Ảnh biến thể --}}
                            @foreach ($product->variants as $variant)
                                @if ($variant->image)
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $variant->image) }}" class="d-block w-100"
                                            alt="Ảnh biến thể">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        {{-- Nút điều hướng trái/phải --}}
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>

                {{-- Thông tin --}}
                <div class="col-md-6">
                    <h1>{{ $product->name }}</h1>

                    {{-- Sao --}}
                    <div class="mb-2 text-warning fs-5">
                        @php $rating = $product->average_rating ?? 0; @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="bi {{ $i <= floor($rating) ? 'bi-star-fill' : ($i - $rating <= 0.5 ? 'bi-star-half' : 'bi-star') }}"></i>
                        @endfor
                        <small class="text-muted ms-2">({{ $product->reviews_count }} đánh giá)</small>
                    </div>

                    {{-- Giá --}}
                    <h4>
                        <strong
                            class="product-price text-danger variant-price fs-4">{{ number_format($product->price_sale, 0, ',', '.') }}đ</strong>
                        @if ($product->price_sale < $product->price)
                            <span
                                class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        @endif
                    </h4>

                    <p>{{ $product->description }}</p>
                    <p><strong>SKU:</strong> <span id="variantSku">{{ $product->sku ?? 'Không có SKU' }}</span></p>
                    <p><strong>Kho:</strong> <span id="stockStatus">Chưa chọn</span></p>

                    {{-- Chọn biến thể --}}
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
                                <select id="variantSize" class="form-select">
                                    <option value="">-- Chọn size --</option>
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">Sản phẩm này hiện không còn hàng.</div>
                    @endif

                    {{-- Form giỏ hàng --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="variant_id">

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1"
                                min="1" disabled>
                        </div>

                        <button class="btn btn-primary w-100" type="submit" id="addToCartBtn" disabled>
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                        </button>
                    </form>
                </div>
            </div>

            {{-- Chi tiết --}}
            <div class="mt-5">
                <h4>Chi tiết sản phẩm</h4>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($product->description ?? 'Chưa có mô tả chi tiết.')) !!}
                </div>
            </div>

            {{-- Bình luận --}}
            <div class="mt-5">
                <h4>Bình luận sản phẩm</h4>

                @auth
                    <form action="{{ route('product.comment', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Đánh giá của bạn:</label>
                            <div class="rating-stars text-warning fs-4 mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="star" id="starRating" required>

                            <label class="form-label">Viết bình luận:</label>
                            <textarea name="content" class="form-control" rows="3" placeholder="Cảm nhận của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Gửi đánh giá</button>
                    </form>
                @else
                    <div class="alert alert-warning">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
                    </div>
                @endauth

                <div class="border rounded p-3">
                    @forelse ($product->feedbacks as $feedback)
                        <div class="mb-3">
                            <strong>{{ $feedback->user->name }}</strong>
                            <small class="text-muted">- {{ $feedback->created_at->diffForHumans() }}</small>

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
            <div class="mt-5">
                <h4>Sản phẩm liên quan</h4>
                <div class="row g-3">
                    @forelse ($relatedProducts as $item)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('product.details', $item->id) }}" class="text-decoration-none">
                                <div class="card border-0 h-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top"
                                        style="height: 180px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <h6 class="card-title text-truncate">{{ $item->name }}</h6>
                                        <p class="card-text text-danger fw-semibold mb-0">
                                            {{ number_format($item->price_sale ?? $item->price, 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">Không có sản phẩm liên quan.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const variants = @json($product->variants);
    const stockBox = document.getElementById('stockStatus');
    const quantityInput = document.getElementById('quantity');
    const variantIdInput = document.getElementById('variant_id');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const priceBox = document.querySelector('.product-price');
    const skuBox = document.getElementById('variantSku');
    const imageBox = document.getElementById('productImage');
    let colorSelected = false;

    const sizeSelect = document.getElementById('variantSize');
    sizeSelect.disabled = true; 

    document.querySelectorAll('.color-swatch').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('border-primary', 'border-2'));
            btn.classList.add('border-primary', 'border-2');
            colorSelected = true;
            renderSizesByColor(btn.dataset.colorId);
        });
    });

    function renderSizesByColor(colorId) {
        sizeSelect.innerHTML = '<option value="">-- Chọn size --</option>';

        const variantsForColor = variants.filter(v => v.color_id == colorId);
        const sizeMap = new Map();

        variantsForColor.forEach(v => {
            if (!sizeMap.has(v.size.id)) {
                sizeMap.set(v.size.id, v);
            }
        });

        sizeMap.forEach((variant, sizeId) => {
            const outOfStock = variant.stock <= 0;
            sizeSelect.innerHTML += `<option value="${sizeId}" ${outOfStock ? 'disabled' : ''}>
                ${variant.size.name} ${outOfStock ? '(Hết hàng)' : ''}
            </option>`;
        });

        sizeSelect.disabled = false;  
        sizeSelect.onchange = null; 

        sizeSelect.addEventListener('change', () => {
            updateVariantInfo(colorId, sizeSelect.value);
        });

    }

    function updateVariantInfo(colorId, sizeId) {
        const selected = variants.find(v => v.color_id == colorId && v.size_id == sizeId);

        const productCarouselEl = document.querySelector('#productCarousel');
        if (productCarouselEl) {
            const productCarousel = bootstrap.Carousel.getInstance(productCarouselEl);
            if (productCarousel) {
                productCarousel.pause();
            }
        }

        if (selected) {
            skuBox.textContent = selected.sku ?? 'Không có SKU';
            stockBox.textContent = selected.stock > 0 ? `Còn ${selected.stock} sản phẩm` : 'Hết hàng';
            variantIdInput.value = selected.id;
            priceBox.textContent = Number(selected.price_sale || selected.price).toLocaleString('vi-VN') + 'đ';

            if (selected.image) {
                let carousel = document.querySelector('#productCarousel .carousel-inner');
                let activeItem = carousel.querySelector('.carousel-item.active');
                let newItem = carousel.querySelector(`img[src$="${selected.image}"]`)?.parentElement;
                if (newItem) {
                    activeItem.classList.remove('active');
                    newItem.classList.add('active');
                }
            }

            if (selected.stock > 0) {
                quantityInput.disabled = false;
                quantityInput.max = selected.stock;
                addToCartBtn.disabled = false;
            } else {
                quantityInput.disabled = true;
                addToCartBtn.disabled = true;
            }
        } else {
            priceBox.textContent = Number(@json($product->price_sale)).toLocaleString('vi-VN') + 'đ';
            skuBox.textContent = 'Không có SKU';
            stockBox.textContent = 'Chưa chọn';
            variantIdInput.value = '';
            quantityInput.disabled = true;
            addToCartBtn.disabled = true;
        }
    }

    document.querySelectorAll('.variant-thumbnail').forEach(img => {
        img.addEventListener('click', () => {
            imageBox.src = img.dataset.image;
        });
    });

    document.querySelectorAll('.rating-stars i').forEach(star => {
        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-value');
            document.getElementById('starRating').value = rating;

            // Reset tất cả sao về icon bi-star
            document.querySelectorAll('.rating-stars i').forEach(s => {
                s.classList.remove('bi-star-fill');
                s.classList.add('bi-star');
            });

            // Đổ đầy các sao tương ứng với rating
            for (let i = 0; i < rating; i++) {
                document.querySelectorAll('.rating-stars i')[i].classList.remove('bi-star');
                document.querySelectorAll('.rating-stars i')[i].classList.add('bi-star-fill');
            }
        });
    });
</script>

@endpush
<style>
    #productImage {
        width: 100%;
        height: 500px;
    }

    .out-of-stock {
        color: red;
        font-weight: bold;
        background-color: #ffcccc;
        padding: 50px;
        border-radius: 5px black;
    }
    #variantSize option:disabled {
    color: #999;
    font-style: italic;
}

</style>
