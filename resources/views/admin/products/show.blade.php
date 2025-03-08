@extends('admin.dashboard')

@section('content')
    <div class="container">
        <h1>{{ $product->name }}</h1>

        <div class="row">
            <div class="col-md-4">
                @if ($product->image)
                    <img src="{{ Storage::url('images/products/' . $product->image) }}" alt="{{ $product->name }}"
                        class="img-fluid">
                @else
                    <p>No Image Available</p>
                @endif
            </div>
            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-sm-3">Description:</dt>
                    <dd class="col-sm-9">{{ $product->description }}</dd>

                    <dt class="col-sm-3">Price:</dt>
                    <dd class="col-sm-9">
                        {{ number_format($product->price, 0, ',', '.') }} VND
                    </dd>

                    <dt class="col-sm-3">Stock:</dt>
                    <dd class="col-sm-9">{{ $product->stock }}</dd>

                    <!-- Display product_detail -->
                    <dt class="col-sm-3">Product Detail:</dt>
                    <dd class="col-sm-9">
                        @if ($product->product_detail)
                            {{ $product->product_detail }}
                        @else
                            <p>No product detail available</p>
                        @endif
                    </dd>

                    <dt class="col-sm-3">Category:</dt>
                    <dd class="col-sm-9">{{ $product->category->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Brand:</dt>
                    <dd class="col-sm-9">{{ $product->brand->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">Created At:</dt>
                    <dd class="col-sm-9">{{ $product->created_at->format('H:i:s d/m/Y') }}</dd>

                    <dt class="col-sm-3">Updated At:</dt>
                    <dd class="col-sm-9">{{ $product->updated_at->format('H:i:s d/m/Y') }}</dd>
                </dl>
            </div>
        </div>

        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">Back to Products</a>
    </div>
@endsection
