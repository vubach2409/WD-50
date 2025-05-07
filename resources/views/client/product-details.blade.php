@extends('layouts.user')

@section('title', $product->name)

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
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
                <!-- Product Image -->
                <div class="col-md-6">
                    <img id="productImage" src="{{ asset('storage/' . $product->image) }}" class="img-fluid mb-3"
                        alt="{{ $product->name }}">
                    <div class="row mt-2">
                        @foreach ($product->variants->take(3) as $variant)
                            <div class="col-4">
                                <img class="variant-thumbnail img-thumbnail"
                                    src="{{ asset('storage/' . ($variant->image ?? $product->image)) }}"
                                    data-image="{{ asset('storage/' . ($variant->image ?? $product->image)) }}"
                                    alt="Ảnh biến thể">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-md-6">
                    <h1 class="d-flex align-items-center gap-2">
                        {{ $product->name }}
                    </h1>

                    <div class="mb-2 text-warning fs-5">
                        @php
                            $rating = $product->average_rating ?? 0;
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            <i
                                class="bi {{ $i <= floor($rating) ? 'bi-star-fill' : ($i - $rating <= 0.5 ? 'bi-star-half' : 'bi-star') }}"></i>
                        @endfor

                        <small class="text-muted ms-2">({{ $product->reviews_count }} đánh giá)</small>
                    </div>


                    <h4><span id="defaultPrice">
                            <strong class="product-price d-block text-danger">
                                {{ number_format($product->price_sale, 0, ',', '.') }}đ
                                @if ($product->price_sale < $product->price)
                                    <span class="text-muted text-decoration-line-through ms-2">
                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </strong>
                        </span></h4>
                    <p>{{ $product->description }}</p>
                    <p><strong>SKU:</strong> <span id="variantSku">{{ $product->sku ?? 'Không có SKU' }}</span></p>
                    <p><strong>Kho:</strong> <span id="stockStatus">Chưa chọn</span></p>

                    @if ($product->variants->count() > 0)
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-semibold">Chọn màu:</label>
                                <select id="variantColor" class="form-select">
                                    <option value="">-- Chọn màu --</option>
                                    @foreach ($product->variants->unique('color_id') as $variant)
                                        <option value="{{ $variant->color_id }}">{{ $variant->color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-semibold">Chọn kích thước:</label>
                                <select id="variantSize" class="form-select">
                                    <option value="">-- Chọn kích thước --</option>
                                </select>
                            </div>                            
                        </div>
                    @else
                        <div class="alert alert-danger">Sản phẩm này hiện không còn hàng.</div>
                    @endif


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

            <!-- Mô tả chi tiết sản phẩm -->
            <div class="mt-5">
                <h4 class="mb-3">Chi tiết sản phẩm</h4>
                <div class="border rounded p-3 bg-light">
                    {!! nl2br(e($product->description ?? 'Chưa có mô tả chi tiết.')) !!}
                </div>
            </div>

            <!-- Bình luận sản phẩm -->
            <div class="mt-5">
                <h4 class="mb-3">Bình luận sản phẩm</h4>

                <!-- Form gửi bình luận -->
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
                    <div class="alert alert-warning">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</div>
                @endauth

                <!-- Danh sách bình luận -->
                <div class="border rounded p-3">
                    @forelse ($product->feedbacks as $feedback)
                        <div class="mb-3">
                            <strong>{{ $feedback->user->name }}</strong>
                            <small class="text-muted">- {{ $feedback->created_at->diffForHumans() }}</small>

                            <!-- Hiển thị sao -->
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


            <!-- Sản phẩm liên quan -->
            <div class="mt-5">
                <h4 class="mb-4">Sản phẩm liên quan</h4>
                <div class="row g-3">
                    @forelse ($relatedProducts as $item)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('product.details', $item->id) }}" class="text-decoration-none">
                                <div class="card border-0 h-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top"
                                        alt="{{ $item->name }}" style="height: 180px; object-fit: cover;">
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
                            <div class="alert alert-info text-center" role="alert">
                                Không có sản phẩm liên quan.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        const variants = @json($product->variants);
        const colorSelect = document.getElementById('variantColor');
        const priceBox = document.getElementById('defaultPrice');
        const skuBox = document.getElementById('variantSku');
        const stockBox = document.getElementById('stockStatus');
        const imageBox = document.getElementById('productImage');
        const quantityInput = document.getElementById('quantity');
        const variantIdInput = document.getElementById('variant_id');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const hasVariants = variants.length > 0;
        const allOutOfStock = hasVariants && variants.every(v => v.stock == 0);


        if (!hasVariants || allOutOfStock) {
            stockBox.textContent = 'Hết hàng';
            quantityInput.value = 0;
            quantityInput.disabled = true;
            addToCartBtn.disabled = true;
        }

        function renderSizesByColor(colorId) {
            const sizeSelect = document.getElementById('variantSize');
            sizeSelect.innerHTML = '<option value="">-- Chọn kích thước --</option>'; // Reset select
            
            const sizesForColor = variants.filter(v => String(v.color_id) === colorId).map(v => v.size);
            const uniqueSizes = [];
            const sizeIds = new Set();
            sizesForColor.forEach(size => {
                if (!sizeIds.has(size.id)) {
                    sizeIds.add(size.id);
                    uniqueSizes.push(size);
                }
            });

            if (uniqueSizes.length > 0) {
                uniqueSizes.forEach(size => {
                    sizeSelect.innerHTML += `<option value="${size.id}">${size.name} cm</option>`;
                });
                sizeSelect.disabled = false;
            } else {
                sizeSelect.innerHTML = '<option value="">Không có kích thước phù hợp</option>';
                sizeSelect.disabled = true;
            }
            sizeSelect.addEventListener('change', updateVariantInfo); // Gọi lại hàm cập nhật khi chọn kích thước
        }


        function updateVariantInfo() {
            const colorId = colorSelect.value;
            const sizeId = document.getElementById('variantSize').value;
            const selected = variants.find(v => String(v.color_id) === colorId && String(v.size_id) === sizeId);

            if (selected) {
                priceBox.textContent = Number(selected.price).toLocaleString('vi-VN');
                skuBox.textContent = selected.sku ?? 'Không có SKU';
                stockBox.textContent = selected.stock > 0 ? `Còn hàng (${selected.stock})` : 'Hết hàng';
                imageBox.src = selected.image ? '/storage/' + selected.image : imageBox.src;
                quantityInput.max = selected.stock;
                quantityInput.disabled = selected.stock === 0;
                quantityInput.value = selected.stock > 0 ? 1 : 0;
                addToCartBtn.disabled = selected.stock === 0;
                variantIdInput.value = selected.id;
            } else {
                priceBox.textContent = '{{ number_format($product->price, 0, ',', '.') }}';
                skuBox.textContent = '{{ $product->sku ?? 'Không có SKU' }}';
                stockBox.textContent = 'Chưa chọn';
                imageBox.src = '{{ asset('storage/' . $product->image) }}';
                quantityInput.value = 0;
                quantityInput.disabled = true;
                addToCartBtn.disabled = true;
            }
        }

        colorSelect.addEventListener('change', () => {
            renderSizesByColor(colorSelect.value);
            updateVariantInfo();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-stars i');
            const input = document.getElementById('starRating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-value');
                    input.value = rating;
                    
                    // Highlight sao
                    stars.forEach(s => {
                        if (s.getAttribute('data-value') <= rating) {
                            s.classList.remove('bi-star');
                            s.classList.add('bi-star-fill');
                        } else {
                            s.classList.remove('bi-star-fill');
                            s.classList.add('bi-star');
                        }
                    });
                });
            });
        });
    </script>
@endpush