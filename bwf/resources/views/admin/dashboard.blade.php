@extends('layouts.app')
@section('title', 'Staff Portal — Bramley Window Factory')
@section('content')
<h1 class="font-display font-bold text-2xl mb-1">Pricing administration</h1>
<p class="text-sm text-[#4A5068] mb-5">Every value is a live database field — changes reprice the whole catalogue instantly. No code deployments needed.</p>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
  @foreach ([['Products', $productCount, route('admin.products')], ['Pricing rules', $ruleCount, route('admin.pricing')], ['Placeholder rates to confirm', $placeholderCount, route('admin.pricing')], ['Quotes issued', $quoteCount, null]] as [$l, $n, $href])
  <a @if($href) href="{{ $href }}" @endif class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm block">
    <div class="font-display font-extrabold text-2xl {{ str_contains($l,'Placeholder') && $n ? 'text-amber-600' : '' }}">{{ $n }}</div>
    <div class="text-sm text-[#4A5068]">{{ $l }}</div>
  </a>
  @endforeach
</div>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="text-xs text-[#4A5068] underline">Sign out</button></form>
@endsection
