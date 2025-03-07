@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm Biến thể</h2>

    <form action="{{ route('admin.product_variants.store', ['product' => $product->id]) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="variation_name" class="form-label">Tên biến thể</label>
            <input type="text" name="variation_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="sku" class="form-label">Mã SKU</label>
            <input type="text" name="sku" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label for="weight" class="form-label">Trọng lượng (kg)</label>
            <input type="number" name="weight" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label for="color_id" class="form-label">Màu sắc</label>
            <select name="color_id" class="form-control">
                <option value="">Chọn kích thước</option>
                <option value="">Không có</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="size_id" class="form-label">Kích thước</label>
            <select name="size_id" class="form-control">
                <option value="">Chọn kích thước</option>
                <option value="">Không có</option> 
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Số lượng kho</label>
            <input type="number" name="stock" class="form-control" value="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.product_variants.index', $product->id) }}" class="btn btn-secondary">Hủy</a>

    </form>
</div>
@endsection
