@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Chỉnh sửa màu sắc</h2>

    <form action="{{ route('admin.colors.update', $color->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Tên màu</th>
                        <td>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Nhập tên màu" value="{{ old('name', $color->name) }}" >
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>Mã màu #...</th>
                        <td>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                placeholder="Nhập mã màu" value="{{ old('code', $color->code) }}">
                            @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
