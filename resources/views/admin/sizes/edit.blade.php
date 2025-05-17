@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="text-primary">Chỉnh sửa kích thước</h2>

    <form action="{{ route('admin.sizes.update', $size->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Kích thước</th>
                        <td>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $size->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
