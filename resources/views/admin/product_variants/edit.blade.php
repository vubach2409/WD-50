@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="text-primary">Chỉnh sửa biến thể</h2>

        <form action="{{ route('admin.product_variants.update', ['product' => $product->id, 'variant' => $variant->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Tên biến thể</th>
                            <td>
                                <input type="text" name="variation_name" class="form-control"
                                    value="{{ old('variation_name', $variant->variation_name) }}" required>
                                @error('variation_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>Mã SKU</th>
                            <td>
                                <input type="text" name="sku" class="form-control"
                                    value="{{ old('sku', $variant->sku) }}" required>
                                @error('sku')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>Giá</th>
                            <td>
                                <input type="number" name="price" class="form-control"
                                    value="{{ old('price', $variant->price) }}" step="0.01" required>
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>Hình ảnh</th>
                            <td>
                                <input type="file" name="image" class="form-control-file">
                                @if ($variant->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $variant->image) }}" width="100"
                                            class="border rounded">
                                    </div>
                                @endif
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>Màu sắc</th>
                            <td>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($colors as $color)
                                        <label class="d-flex flex-column align-items-center" style="cursor: pointer;">
                                            <input type="radio" name="color_id" value="{{ $color->id }}"
                                                class="form-check-input mb-1"
                                                {{ old('color_id', $variant->color_id ?? '') == $color->id ? 'checked' : '' }}>
                                            <div
                                                style="width: 40px; height: 40px; background-color: {{ $color->code }}; border: 1px solid #ccc; border-radius: 4px;">
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('color_id')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>Kích thước</th>
                            <td>
                                <select name="size_id" class="form-control">
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}"
                                            {{ old('size_id', $variant->size_id) == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('size_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>Số lượng kho</th>
                            <td>
                                <input type="number" name="stock" class="form-control"
                                    value="{{ old('stock', $variant->stock) }}">
                                @error('stock')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('admin.product_variants.index', $product->id) }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
@endsection
