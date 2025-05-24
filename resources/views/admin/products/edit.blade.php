@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary">Chỉnh sửa sản phẩm</h2>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <td>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <th>Giá min</th>
                            <td>
                                <input type="number" name="price_sale"
                                    class="form-control @error('price_sale') is-invalid @enderror"
                                    placeholder="Nhập giá sản phẩm" value="{{ old('price_sale', $product->price_sale) }}">
                                @error('price_sale')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <th>Giá max</th>
                            <td>
                                <input type="number" name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $product->price) }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        
                        <tr>
                            <th>Danh mục</th>
                            <td>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <th>Thương hiệu</th>
                            <td>
                                <select name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                    <option value="">-- Chọn thương hiệu --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <th>Ảnh sản phẩm</th>
                            <td>
                                <input type="file" name="image"
                                    class="form-control-file @error('image') is-invalid @enderror">
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                @if ($product->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" width="130">
                                    </div>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Mô tả ngắn</th>
                            <td>
                                <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="4">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <th>Mô tả chi tiết sản phẩm</th>
                            <td>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
@endsection