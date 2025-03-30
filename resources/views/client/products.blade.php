@extends('layouts.user')

@section('title', 'Products')

@section('content')
    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div class="row">
                @foreach($products as $product)
                <!-- Start Column -->
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item" href="{{ route('product.details', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid product-thumbnail" alt="{{ $product->name }}">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <strong class="product-price">${{ number_format($product->price, 2) }}</strong>

                        <span class="icon-cross">
                            <img src="{{ asset('clients/images/cross.svg') }}" class="img-fluid">
                        </span>
                    </a>
                </div>
                <!-- End Column -->
                @endforeach
            </div>
        </div>
    </div>
@endsection
