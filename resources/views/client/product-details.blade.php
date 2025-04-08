@extends('layouts.user')

@section('title', $product->name)

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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
                    <h1>{{ $product->name }}</h1>
                    <h4><span id="defaultPrice">{{ number_format($product->price, 0, ',', '.') }}</span> VNĐ</h4>

                    <p>{{ $product->description }}</p>
                    <p><strong>SKU:</strong> <span id="variantSku">{{ $product->sku ?? 'Không có SKU' }}</span></p>
                    <p><strong>Kho:</strong> <span id="stockStatus">Chưa chọn</span></p>

                    <!-- Lựa chọn biến thể -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Chọn màu:</label>
                            <select id="variantColor" class="form-select">
                                <option value="">-- Chọn màu --</option>
                                @foreach ($product->variants->unique('color_id') as $variant)
                                    <option value="{{ $variant->color_id }}">{{ $variant->color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Chọn kích thước:</label>
                            <select id="variantSize" class="form-select">
                                <option value="">-- Chọn kích thước --</option>
                                @foreach ($product->variants->unique('size_id') as $variant)
                                    <option value="{{ $variant->size_id }}">{{ $variant->size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Thêm vào giỏ -->
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="variant_id">

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1"
                                min="1" disabled>
                        </div>

                        <button class="btn btn-primary" type="submit" id="addToCartBtn" disabled>Thêm vào giỏ hàng</button>
                    </form>

                    @if (session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const variants = @json($product->variants);
        const colorSelect = document.getElementById('variantColor');
        const sizeSelect = document.getElementById('variantSize');
        const priceBox = document.getElementById('defaultPrice');
        const skuBox = document.getElementById('variantSku');
        const stockBox = document.getElementById('stockStatus');
        const imageBox = document.getElementById('productImage');
        const quantityInput = document.getElementById('quantity');
        const variantIdInput = document.getElementById('variant_id');
        const addToCartBtn = document.getElementById('addToCartBtn');

        function updateVariantInfo() {
            const colorId = colorSelect.value;
            const sizeId = sizeSelect.value;

            const selected = variants.find(v => String(v.color_id) === colorId && String(v.size_id) === sizeId);

            if (selected) {
                priceBox.textContent = Number(selected.price).toLocaleString('vi-VN');
                skuBox.textContent = selected.sku ?? 'Không có SKU';
                stockBox.textContent = selected.stock > 0 ? `Còn hàng (${selected.stock})` : 'Hết hàng';
                imageBox.src = selected.image ? '/storage/' + selected.image : '{{ asset('storage/' . $product->image) }}';

                quantityInput.max = selected.stock;
                quantityInput.disabled = selected.stock === 0;
                quantityInput.value = selected.stock > 0 ? 1 : 0;

                addToCartBtn.disabled = selected.stock === 0;
                variantIdInput.value = selected.id;
            } else {
                // Reset nếu không chọn đúng
                priceBox.textContent = '{{ number_format($product->price, 0, ',', '.') }}';
                skuBox.textContent = '{{ $product->sku ?? 'Không có SKU' }}';
                stockBox.textContent = 'Chưa chọn';
                imageBox.src = '{{ asset('storage/' . $product->image) }}';
                quantityInput.value = 0;
                quantityInput.disabled = true;
                addToCartBtn.disabled = true;
            }
        }

        colorSelect.addEventListener('change', updateVariantInfo);
        sizeSelect.addEventListener('change', updateVariantInfo);
    </script>
@endpush
