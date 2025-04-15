<?php

namespace App\Http\Controllers\Admin;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'code' => 'required|unique:vouchers',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
        ]);
    
        // Lấy tất cả dữ liệu từ request
        $data = $request->all();
    
        // Đảm bảo giá trị 'is_active' được gửi đúng (true khi checkbox được chọn, false khi không được chọn)
        $data['is_active'] = $request->has('is_active') ? true : false;
    
        // Tạo voucher mới trong cơ sở dữ liệu
        Voucher::create($data);
    
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher created successfully.');
    }
    
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
        ]);


        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? true : false;

        $voucher->update($data);


        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Voucher deleted.');
    }
}
