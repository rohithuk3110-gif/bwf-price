<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Quote;
use App\Services\PricingEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function store(Request $request, Product $product, PricingEngine $engine)
    {
        $r = $engine->price($product->load('attributeGroup.attributes.options'),
            $request->input('config', []), $request->input('price_list', 'RETAIL'), $request->input('vat', 'STANDARD'));
        abort_unless($r['ok'], 422, implode(' ', $r['errors'] ?? ['Invalid configuration']));

        $quote = Quote::create([
            'reference' => 'BWF-'.strtoupper(Str::random(6)),
            'price_list_code' => $request->input('price_list', 'RETAIL'),
            'vat_code' => $request->input('vat', 'STANDARD'),
            'status' => 'draft',
            'items_total' => $r['items_total'], 'delivery' => $r['delivery'],
            'vat_amount' => $r['vat_amount'], 'grand_total' => $r['grand_total'],
            'lead_time' => $r['lead_time'], 'valid_until' => now()->addDays(30),
        ]);
        $quote->items()->create([
            'product_id' => $product->id, 'quantity' => $r['quantity'],
            'width_mm' => (int)($r['config']['width'] ?? 0), 'height_mm' => (int)($r['config']['height'] ?? 0),
            'configuration' => $r['config'], 'breakdown' => $r['lines'],
            'unit_price' => $r['unit_price'], 'line_total' => $r['items_total'],
        ]);
        return redirect()->route('quote.show', $quote);
    }
    public function show(Quote $quote)
    {
        $quote->load('items.product.category');
        return view('quote.show', compact('quote'));
    }
    public function pdf(Quote $quote)
    {
        $quote->load('items.product.category');
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return \Barryvdh\DomPDF\Facade\Pdf::loadView('quote.pdf', compact('quote'))
                ->download($quote->reference.'.pdf');
        }
        return view('quote.pdf', compact('quote')); // print-friendly fallback
    }
}
