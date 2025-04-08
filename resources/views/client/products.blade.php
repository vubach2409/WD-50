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
                            <strong class="product-price">{{ number_format($product->price_sale, 0, ',', '.') }}đ -
                                {{ number_format($product->price, 0, ',', '.') }}đ</strong>


                            <span class="icon-cross">
                                <img src="{{ asset('clients/images/cross.svg') }}" class="img-fluid">
                            </span>


                        </a>
                    </div>
            </div>
            <!-- End Column -->
            @endforeach
        </div>
    </div>
    </div>
@endsection
