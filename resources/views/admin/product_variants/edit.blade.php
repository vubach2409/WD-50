@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa biến thể</h2>

    <form action="{{ route('admin.product_variants.update', ['product' => $product->id, 'variant' => $variant->id]) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="variation_name" class="form-label">Tên biến thể</label>
            <input type="text" name="variation_name" class="form-control" value="{{ $variant->variation_name }}" required>
        </div>

        <div class="mb-3">
            <label for="sku" class="form-label">Mã SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ $variant->sku }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $variant->price }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
            @if($variant->image)
                <img src="{{ asset('storage/' . $variant->image) }}" width="100" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label for="weight" class="form-label">Trọng lượng (kg)</label>
            <input type="number" name="weight" class="form-control" value="{{ $variant->weight }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="color_id" class="form-label">Màu sắc</label>
            <select name="color_id" class="form-control">
                <option value="">Chọn màu</option>
                <option value="">Không có</option> <!-- Thêm tùy chọn "Không có" -->
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                        {{ $color->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="size_id" class="form-label">Kích thước</label>
            <select name="size_id" class="form-control">
                <option value="">Chọn kích thước</option>
                <option value="">Không có</option>
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>
                        {{ $size->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Số lượng kho</label>
            <input type="number" name="stock" class="form-control" value="{{ $variant->stock }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.product_variants.index', $product->id) }}" class="btn btn-secondary">Hủy</a>

    </form>
</div>
@endsection
