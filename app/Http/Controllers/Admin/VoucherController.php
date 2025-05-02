<?php

namespace App\Http\Controllers\Admin;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    /**
     * Hiển thị danh sách voucher.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Hiển thị form tạo voucher mới.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Lưu voucher mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers|max:255',
            'type' => 'required|in:fixed,percent',
            'value' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percent' && $value > 100) {
                        $fail('Giá trị phần trăm không được vượt quá 100%.');
                    }
                },
            ],
            'min_order_amount' => 'required|integer|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date|before_or_equal:expires_at',
            'expires_at' => 'nullable|date|after_or_equal:' . now()->toDateString(),
            'is_active' => 'boolean',
        ], [
            'expires_at.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày hiện tại.',
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'type.in' => 'Loại giảm giá không hợp lệ.',
            'value.required' => 'Giá trị giảm là bắt buộc.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'min_order_amount.required' => 'Vui lòng nhập đơn hàng tối thiểu.',
            'min_order_amount.integer' => 'Đơn hàng tối thiểu phải là số nguyên.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0.',
            'starts_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'starts_at.before_or_equal' => 'Ngày bắt đầu phải sau hoặc bằng ngày hiện tại.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');  // Sử dụng boolean để đảm bảo đúng kiểu

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa voucher.
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Cập nhật voucher trong cơ sở dữ liệu.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $voucher->id . '|max:255',
            'type' => 'required|in:fixed,percent',
            'value' => [
                'required',
                'numeric',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'percent' && $value > 100) {
                        $fail('Giá trị phần trăm không được vượt quá 100%.');
                    }
                },
            ],
            'min_order_amount' => 'required|integer|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date|before_or_equal:expires_at',
            'expires_at' => 'nullable|date|after_or_equal:' . now()->toDateString(),
            'is_active' => 'boolean',
        ], [
            'expires_at.after_or_equal' => 'Ngày hết hạn phải lớn hơn hoặc bằng ngày hiện tại.',
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'type.in' => 'Loại giảm giá không hợp lệ.',
            'value.required' => 'Giá trị giảm là bắt buộc.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'min_order_amount.required' => 'Vui lòng nhập đơn hàng tối thiểu.',
            'min_order_amount.integer' => 'Đơn hàng tối thiểu phải là số nguyên.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn 0.',
            'starts_at.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'starts_at.before_or_equal' => 'Ngày bắt đầu phải sau hoặc bằng ngày hiện tại.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');  // Sử dụng boolean

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Xóa mã giảm giá thành công!');
    }
}
