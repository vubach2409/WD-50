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
                        <th style="width: 20%;">Chọn màu</th>
                        <td>
                            <input type="color" name="code" class="form-control form-control-color @error('code') is-invalid @enderror"
                                value="{{ old('code', $color->code) }}">
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
