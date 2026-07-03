@extends('layouts.app')
@section('title', 'Pricing rules — Staff Portal')
@section('content')
<nav class="text-xs mb-3"><a class="font-semibold" href="{{ route('admin.dashboard') }}">Staff Portal</a> › Pricing rules</nav>
<h1 class="font-display font-bold text-2xl mb-4">Option &amp; rule pricing</h1>
<div class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm">
@foreach ($rules as $r)
  <form method="POST" action="{{ route('admin.pricing.update', $r) }}" class="flex items-center gap-3 py-1.5 border-b border-[#FAF9F6]">
    @csrf @method('PUT')
    <span class="text-[10px] font-semibold text-navy w-24 shrink-0">{{ $r->code }}</span>
    <span class="text-xs text-[#4A5068] flex-1">{{ $r->label }} <span class="text-[#8A90B8]">({{ $r->component }} · {{ $r->method }})</span></span>
    <input name="value" type="number" step="0.001" value="{{ $r->value + 0 }}"
      class="w-24 border-2 rounded-lg px-2 py-1 text-sm {{ $r->is_placeholder ? 'bg-amber-50 border-amber-300' : ($r->is_verified ? 'bg-green-50 border-green-500' : 'border-[#E6E4DD]') }}">
    <button class="bg-navy text-white rounded-full px-4 py-1 text-xs font-display font-semibold">Save</button>
  </form>
@endforeach
<p class="text-[10px] text-amber-700 mt-3">Amber = placeholder example rate awaiting confirmed factory pricing · Green = verified.</p>
</div>
@endsection
