@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa sản phẩm</h2>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control">
            @if($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" width="100">
                </div>
            @endif
        </div>
        
        <div class="mb-3">
            <label class="form-label">Kho hàng</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Thương hiệu</label>
            <select name="brand_id" class="form-control">
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
