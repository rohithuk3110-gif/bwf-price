<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('category_id')->orderBy('sort_order')->get();
        return view('admin.products', compact('products'));
    }
    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['base_price' => 'required|numeric|min:0', 'is_active' => 'sometimes|boolean']);
        AuditLog::create(['user_id' => $request->user()->id, 'action' => 'update', 'entity' => 'product',
            'entity_id' => $product->id, 'old_value' => ['base_price' => $product->base_price], 'new_value' => $data]);
        $product->update($data + ['price_verified' => true]);
        return back()->with('ok', $product->name.' updated.');
    }
}
