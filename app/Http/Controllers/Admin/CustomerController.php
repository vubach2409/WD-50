<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $user = User::select('id', 'name', 'email', 'role', 'avatar', 'phone', 'created_at', 'updated_at')
            ->where('role', '!=', 'admin') // Không lấy user có role admin
            ->find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User không tồn tại hoặc là Admin.');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::where('role', '!=', 'admin')->find($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa Admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User đã được xóa.');
    }
    }


