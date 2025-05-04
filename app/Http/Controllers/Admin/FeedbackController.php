<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedbacks;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedbacks::with(['product', 'user'])->latest()->paginate(10);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function destroy($id)
    {
        $feedback = Feedbacks::findOrFail($id);
        $feedback->delete();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Đã xóa đánh giá!');
    }
    public function toggleHide($id)
    {
        $feedback = Feedbacks::findOrFail($id);
        $feedback->is_hidden = !$feedback->is_hidden; // Đảo trạng thái
        $feedback->save();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Cập nhật trạng thái ẩn/hiện thành công.');
    }

}
