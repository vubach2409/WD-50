<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedbacks;
use App\Models\ProductVariant;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $variantId = $request->input('variant_id');

        $variants = ProductVariant::with('product')->get();

        $feedbacks = Feedbacks::with(['user', 'variation.product'])
            ->when($variantId, function ($query, $variantId) {
                return $query->where('variation_id', $variantId);
            })
            ->latest()
            ->paginate(10);

        return view('admin.feedback.index', compact('feedbacks', 'variants', 'variantId'));
    }

    public function destroy($id)
    {
        $fb = Feedbacks::findOrFail($id);
        $fb->delete();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Xóa đánh giá thành công.');
    }

    public function toggleHide($id)
    {
        $fb = Feedbacks::findOrFail($id);
        $fb->is_hidden = !$fb->is_hidden;
        $fb->save();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Cập nhật trạng thái đánh giá thành công.');
    }
}
