<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PricingRule;
use App\Models\Quote;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'ruleCount' => PricingRule::count(),
            'placeholderCount' => PricingRule::where('is_placeholder', true)->count() + Product::where('price_verified', false)->count(),
            'quoteCount' => Quote::count(),
        ]);
    }
}
