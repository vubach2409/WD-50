@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Danh sách biến thể của Sản phẩm</h2>
    
    <form action="{{ route('admin.product_variants.search') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sản phẩm..." value="{{ request('keyword') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </div>
    </form>
    

    <div class="table-responsive">
        <table class="table table-hover table-bordered text-center" id="variantsTable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th class="text-left">Tên sản phẩm</th>
                    <th>Số lượng biến thể</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                <tr>
                    <td class="text-center align-middle">{{ $index + 1 }}</td> 
                    <td class="text-left align-middle">{{ $product->name }}</td>
                    <td class="text-center align-middle">{{ $product->variants->count() }}</td>
                    <td class="text-center align-middle">
                        <a href="{{ route('admin.product_variants.index', $product->id) }}" class="btn btn-info">
                            Xem biến thể
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection