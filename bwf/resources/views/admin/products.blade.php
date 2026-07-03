@extends('layouts.app')
@section('title', 'Products — Staff Portal')
@section('content')
<nav class="text-xs mb-3"><a class="font-semibold" href="{{ route('admin.dashboard') }}">Staff Portal</a> › Products</nav>
<h1 class="font-display font-bold text-2xl mb-4">Per-product base prices</h1>
<p class="text-sm text-[#4A5068] mb-4">Each design is an independent record with its own URL and pricing. Ex-VAT trade base shown.</p>
<div class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm">
@foreach ($products as $p)
  <form method="POST" action="{{ route('admin.products.update', $p) }}" class="flex items-center gap-3 py-1 border-b border-[#FAF9F6]">
    @csrf @method('PUT')
    <span class="text-[10px] text-[#4A5068] w-56 shrink-0 truncate">/{{ $p->category->slug }}/<b class="text-navy">{{ $p->slug }}</b></span>
    <span class="text-xs flex-1">{{ $p->name }}</span>
    <input name="base_price" type="number" step="0.01" value="{{ $p->base_price + 0 }}"
      class="w-28 border-2 rounded-lg px-2 py-1 text-sm {{ $p->price_verified ? 'bg-green-50 border-green-500' : 'bg-amber-50 border-amber-300' }}">
    <button class="bg-navy text-white rounded-full px-4 py-1 text-xs font-display font-semibold">Save</button>
  </form>
@endforeach
</div>
@endsection
