@extends('admin.dashboard')

@section('content')
    <div class="container">
        <h1>Create Product</h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                    required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <!-- Product detail field -->
            <div class="form-group">
                <label for="product_detail">Product Detail</label>
                <textarea name="product_detail" id="product_detail" class="form-control">{{ old('product_detail') }}</textarea>
                @error('product_detail')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}"
                    required min="0.01" step="0.01">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}"
                    required min="1">
                @error('stock')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="form-control" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control-file">
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Create Product</button>
        </form>
    </div>
@endsection
