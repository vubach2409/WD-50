<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Brand;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreProductRequest; // Import StoreProductRequest
use App\Http\Requests\UpdateProductRequest; // Import UpdateProductRequest

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::with(['category', 'brand']);

            // Filter by date range (if provided)
            if ($request->startDate && $request->endDate) {
                $query->whereBetween('created_at', [
                    $request->startDate . ' 00:00:00',
                    $request->endDate . ' 23:59:59'
                ]);
            }

            // Filter by product name (if provided)
            if ($request->productNameSearch) {
                $query->where('name', 'like', '%' . $request->productNameSearch . '%');
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('image', function ($product) {
                    if ($product->image) {
                        return '<img src="' . Storage::url('images/products/' . $product->image) . '" height="50" width="50" alt="' . $product->name . '">';
                    }
                    return 'No Image';
                })
                ->editColumn('category', function ($product) {
                    return $product->category ? $product->category->name : 'N/A';
                })
                ->editColumn('brand', function ($product) {
                    return $product->brand ? $product->brand->name : 'N/A';
                })
                ->addColumn('actions', function ($product) {
                    return '
                    <a href="' . route('admin.products.show', $product->id) . '" class="btn btn-info btn-sm">Show</a>
                    <a href="' . route('admin.products.edit', $product->id) . '" class="btn btn-primary btn-sm">Edit</a>
                    <form action="' . route('admin.products.destroy', $product->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
                })
                ->rawColumns(['image', 'actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
-              $image->move(public_path('img'), $imageName); // Move to public/img
-              $validatedData['image'] = $imageName;
+               $image->move(public_path('img'), $imageName); // Move to public/img
+               $validatedData['image'] = $imageName;
        }

        Product::create($validatedData);
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
-                 Storage::delete(public_path('img').'/'.$product->image);// Delete old image from public/img
+                   Storage::delete(public_path('img') . '/' . $product->image); // Delete old image from public/img
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
-              $image->move(public_path('img'), $imageName); // Move to public/img
-              $validatedData['image'] = $imageName;
+               $image->move(public_path('img'), $imageName); // Move to public/img
+               $validatedData['image'] = $imageName;
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
      {
          //   $product = Product::findOrFail($id); // No need to get deleted product
          $product = Product::withTrashed()->findOrFail($id); // Use withTrashed to get soft deleted product too
          if ($product->trashed()) {
              // If the product is already soft-deleted, then perma delete it
              if ($product->image) {
-                    Storage::delete(public_path('img').'/'.$product->image);// Delete old image from public/img
+                    Storage::delete(public_path('img') . '/' . $product->image); // Delete old image from public/img
              }
              $product->forceDelete();
              return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
          } else {
              // if the product is not deleted, then soft delete it
              $product->delete();

              return redirect()->route('admin.products.index')->with('success', 'Product moved to trash successfully!');
          }
      }
}
