@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                @foreach ($products as $product)
                    <!-- Start Column -->
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <a class="product-item" href="{{ route('product.details', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid product-thumbnail"
                                alt="{{ $product->name }}">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <strong class="product-price">${{ number_format($product->price, 2) }}</strong>

                        </a>
                        <!-- Form thêm vào giỏ hàng -->
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1" max="{{ $product->stock }}">
                            <!-- Mặc định 1 sản phẩm -->
                            <button type="submit" class="btn btn-success w-100">Add to Cart</button>
                        </form>
                    </div>
                    <!-- End Column -->
                @endforeach
            </div>
        </div>
    </div>
@endsection
