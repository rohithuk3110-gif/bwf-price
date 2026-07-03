<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function home()
    {
        $tops = Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->with('children')->get();
        return view('shop.home', compact('tops'));
    }
    public function category(Category $category)
    {
        $category->load('children.products', 'products', 'parent');
        // top-level -> range tiles; leaf -> product cards (product-first)
        return $category->parent_id
            ? view('shop.subcategory', ['sub' => $category])
            : view('shop.category', ['cat' => $category]);
    }
    public function product(Category $category, Product $product)
    {
        abort_unless($product->category_id === $category->id && $product->is_active, 404);
        $product->load('attributeGroup.attributes.options', 'category.parent');
        return view('shop.product', compact('product'));
    }
}
