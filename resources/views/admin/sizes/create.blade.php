@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Thêm kích thước</h2>

    <form action="{{ route('admin.sizes.store') }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Kích thước</th>
                        <td>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Nhập kích thước" value="{{ old('name') }}" autofocus>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
