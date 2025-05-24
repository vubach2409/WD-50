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
                                <img src="{{ asset('storage/' . $product->image) }}" class="d-block w-100 view-image"
                                    alt="{{ $product->name }}" style="cursor: zoom-in;">
                            </div>
                            {{-- Ảnh biến thể --}}
                            @foreach ($product->variants as $variant)
                                @if ($variant->image)
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $variant->image) }}"
                                            class="d-block w-100 view-image" alt="Ảnh biến thể" style="cursor: zoom-in;">
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
                        <strong id="variantPrice" class="text-danger fw-bold"></strong>
                        <span id="defaultPrice">
                            <strong class="text-danger fw-bold">
                                {{ number_format($product->price_sale, 0, ',', '.') }} ₫ -
                            </strong>
                            @if ($product->price_sale < $product->price)
                                <span class="text-danger fw-bold">
                                    {{ number_format($product->price, 0, ',', '.') }} ₫
                                </span>
                            @endif
                        </span>
                    </h4>

                    <p>{{ $product->short_description }}</p>
                    <p><strong>SKU:</strong> <span id="variantSku">{{ $product->sku ?? 'Chưa chọn' }}</span></p>
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
                                    <option value="" class="text-danger">Vui lòng chọn màu sắc trước!</option>
                                </select>
                            </div>
                        </div>
                        @if (!Auth::check() || (Auth::check() && Auth::user()->role !== 'nhanvien'))
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
                        @endif
                    @else
                        <div class="alert alert-danger">Sản phẩm này hiện không còn hàng.</div>
                    @endif


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
                <h4>Đánh giá sản phẩm</h4>

                @auth
                    {{-- <form action="{{ route('product.comment', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Đánh giá của bạn:</label>
                            <div class="rating-stars text-warning fs-4 mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="star" id="starRating" required>

                            <label class="form-label">Nội dung: </label>
                            <textarea name="content" class="form-control" rows="3" placeholder="Cảm nhận của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Gửi đánh giá</button>
                    </form> --}}
                @else
                    <div class="alert alert-warning">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
                    </div>
                @endauth

                <div class="border rounded p-3">
                    @forelse ($product->feedbacks as $feedback)
                        <div class="mb-3">
                            <strong>{{ $feedback->user->name }}</strong>
                            <small class="text-muted">- {{ $feedback->created_at->diffForHumans() }}</small>

                            {{-- Hiển thị biến thể nếu có --}}
                            @if ($feedback->variation)
                                <div class="text-dark mb-1">
                                    Biến thể:
                                    {{ $feedback->variation->variation_name ?? 'Mặc định' }}
                                    @if ($feedback->variation->color)
                                        - Màu:
                                        <span
                                            style="display:inline-block; width:16px; height:16px; background-color: {{ $feedback->variation->color->code }}; border:1px solid #ccc; border-radius:4px; vertical-align:middle;"></span>
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


                {{-- Sản phẩm liên quan --}}
                <div class="mt-5 popular-product">
                    <h4 class="mb-4 text-center">Sản phẩm liên quan</h4>
                    <div class="row justify-content-center">
                        @forelse ($relatedProducts as $product)
                                        <div class="col-6 col-md-3">
                                <div class="product-item position-relative overflow-hidden">
                                    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                                        <div class="product-image position-relative">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid"
                                                alt="{{ $product->name }}">

                                            @if ($product->is_new)
                                                <span class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                                            @elseif($product->price_sale < $product->price)
                                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">Hot</span>
                                            @endif
                                        </div>

                                        <div class="product-info p-3">
                                            <h5 class="product-title text-dark mb-1" style="font-size: 1rem;">
                                                {{ $product->name }}
                                            </h5>
                                            <p class="product-price mb-2">
                                                <span class="text-danger fw-bold">
                                                    {{ number_format($product->price_sale ?? $product->price, 0, ',', '.') }}đ -
                                                </span>
                                                @if ($product->price_sale && $product->price_sale < $product->price)
                                                    <span class="text-danger fw-bold">
                                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                                    </span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="product-action-btn position-absolute top-50 start-50 translate-middle">
                                            <a href="{{ route('product.details', $product->id) }}"
                                                class="btn btn-outline-primary btn-sm rounded-pill px-2 py-2">
                                                <i class="bi bi-eye me-1"></i> Xem chi tiết
                                            </a>
                                        </div>
                                    </a>
                                </div>
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

        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body p-0">
                        <img src="" id="modalImage" class="img-fluid w-100 rounded shadow">
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
            const variantPriceBox = document.getElementById('variantPrice');
            const defaultPriceBox = document.getElementById('defaultPrice');
            let colorSelected = false;

            const sizeSelect = document.getElementById('variantSize');
            sizeSelect.disabled = true;

            document.querySelectorAll('.color-swatch').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.color-swatch').forEach(b => b.classList.remove('border-primary',
                        'border-2'));
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
                    const variantPrice = Number(selected.price_sale || selected.price);
                    variantPriceBox.textContent = variantPrice.toLocaleString('vi-VN') + ' ₫';
                    variantPriceBox.classList.remove('d-none');
                    defaultPriceBox.classList.add('d-none');

                    const variantDescriptionEl = document.getElementById('variantDescription');
                    if (selected.description && selected.description.trim() !== '') {
                        variantDescriptionEl.textContent = selected.description;
                        variantDescriptionEl.style.display = 'block';
                    } else {
                        variantDescriptionEl.style.display = 'none';
                        variantDescriptionEl.textContent = '';
                    }
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
                    variantDescriptionEl.style.display = 'none';
                    variantDescriptionEl.textContent = '';
                    priceBox.textContent = Number(@json($product->price_sale)).toLocaleString('vi-VN') + ' ₫';
                    skuBox.textContent = 'Chưa chọn';
                    stockBox.textContent = 'Chưa chọn';
                    variantIdInput.value = '';
                    quantityInput.disabled = true;
                    addToCartBtn.disabled = true;
                    variantPriceBox.classList.add('d-none');
                    defaultPriceBox.classList.remove('d-none');
                }
            }

            document.querySelectorAll('.variant-thumbnail').forEach(img => {
                img.addEventListener('click', () => {
                    imageBox.src = img.dataset.image;
                });
            });

            document.querySelectorAll('.view-image').forEach(img => {
                img.addEventListener('click', function() {
                    const modalImg = document.getElementById('modalImage');
                    modalImg.src = this.src;
                    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                    modal.show();
                });
            });

            document.querySelectorAll('.rating-stars i').forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-value');
                    document.getElementById('starRating').value = rating;

                    document.querySelectorAll('.rating-stars i').forEach(s => {
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                    });

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
