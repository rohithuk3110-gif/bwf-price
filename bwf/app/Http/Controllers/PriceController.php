<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Services\PricingEngine;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function __invoke(Request $request, Product $product, PricingEngine $engine)
    {
        $result = $engine->price(
            $product->load('attributeGroup.attributes.options'),
            $request->input('config', []),
            $request->input('price_list', 'RETAIL'),
            $request->input('vat', 'STANDARD'),
        );
        return response()->json($result);
    }
}
