@extends('layouts.admin')

@section('content')
    <div class="card">
        <h4 class="card-header text-primary">
            {{ __('Chi tiết sản phẩm') }}
        </h4>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>{{ __('Tên sản phẩm') }}</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Mô tả') }}</th>
                        <td>{{ $product->description ?? 'Không có mô tả' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Giá') }}</th>
                        <td>{{ number_format($product->price) }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>{{ __('Giá khuyến mãi') }}</th>
                        <td>{{ number_format($product->price_sale) }} VNĐ</td>
                    </tr>
                    <tr>
                        <th>{{ __('Danh mục') }}</th>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Thương hiệu') }}</th>
                        <td>{{ $product->brand->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Hình ảnh') }}</th>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
                            @else
                                {{ __('Không có hình ảnh') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Ngày tạo') }}</th>
                        <td>{{ $product->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Ngày cập nhật') }}</th>
                        <td>{{ $product->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
            <center class="mt-3">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">{{ __('Quay lại') }}</a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">{{ __('Chỉnh sửa') }}</a>
                <a href="{{ route('admin.product_variants.index', ['product' => $product->id]) }}" class="btn btn-info">Biến thể</a>
            </center>
        </div>
    </div>
@endsection