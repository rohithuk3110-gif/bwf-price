@extends('layouts.app')
@section('title', 'Quotation '.$quote->reference.' — Bramley Window Factory')
@section('content')
@php $item = $quote->items->first(); $p = $item->product; @endphp
<div class="max-w-xl mx-auto bg-white border border-[#E6E4DD] rounded-2xl p-6 shadow-sm">
  <div class="flex justify-between items-center">
    <h1 class="font-display font-extrabold text-xl">Your Quotation</h1>
    <span class="text-brand font-semibold text-sm">{{ $quote->reference }}</span>
  </div>
  <p class="text-xs text-[#4A5068] mt-1 mb-4">Valid until {{ $quote->valid_until->format('j M Y') }} · Estimated lead time {{ $quote->lead_time }}</p>
  @include('shop._diagram', ['p' => $p])
  <h2 class="font-display font-bold mt-3">{{ $p->name }} × {{ $item->quantity }} — {{ $item->width_mm }} × {{ $item->height_mm }} mm</h2>
  <div class="mt-3 text-sm"><div class="text-[10px] text-[#4A5068] uppercase tracking-wide mb-1">Price build-up (ex VAT)</div>
    @foreach ($item->breakdown as $l)
      <div class="flex justify-between py-1 border-b border-[#FAF9F6]"><span>{{ $l['label'] }}</span><span>£{{ number_format($l['amount'], 2) }}</span></div>
    @endforeach
    <div class="flex justify-between py-1.5 font-semibold"><span>Delivery</span><span>£{{ number_format($quote->delivery, 2) }}</span></div>
    <div class="flex justify-between py-1.5 font-semibold"><span>VAT</span><span>£{{ number_format($quote->vat_amount, 2) }}</span></div>
    <div class="flex justify-between py-2 font-display font-extrabold border-t-2 border-navy text-lg"><span>Total inc VAT</span><span>£{{ number_format($quote->grand_total, 2) }}</span></div>
  </div>
  <div class="flex gap-3 mt-5">
    <a href="{{ route('quote.pdf', $quote) }}" class="flex-1 text-center bg-brand text-white rounded-full py-2.5 font-display font-semibold text-sm">Download PDF</a>
    <a href="{{ route('product', [$p->category, $p]) }}" class="flex-1 text-center bg-navy text-white rounded-full py-2.5 font-display font-semibold text-sm">Edit configuration</a>
  </div>
</div>
@endsection
