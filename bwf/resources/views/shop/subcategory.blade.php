@extends('layouts.app')
@section('title', $sub->name.' — Bramley Window Factory')
@section('content')
<nav class="text-xs text-[#4A5068] mb-3">
  <a class="font-semibold text-navy" href="{{ route('home') }}">Home</a> ›
  <a class="font-semibold text-navy" href="{{ route('category', $sub->parent) }}">{{ $sub->parent->name }}</a> › {{ $sub->name }}
</nav>
<h1 class="font-display font-bold text-2xl">{{ $sub->name }}</h1>
<p class="text-sm text-[#4A5068] mt-1 mb-5">{{ $sub->blurb }} Every design below is its own product with its own configurator and price.</p>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @foreach ($sub->products->where('is_active', true) as $p)
  <div class="bg-white border border-[#E6E4DD] rounded-2xl p-4 shadow-sm flex flex-col">
    @include('shop._diagram', ['p' => $p])
    <div class="font-display font-bold mt-2">{{ $p->name }}</div>
    <div class="text-xs text-[#4A5068] mt-1 mb-3 flex-1">{{ $p->description }}</div>
    <div class="flex items-center justify-between">
      <div><div class="text-[10px] text-[#4A5068]">From</div>
        <div class="font-display font-bold">£{{ number_format($p->fromPrice(), 2) }}</div></div>
      <a href="{{ route('product', [$sub, $p]) }}" class="bg-brand text-white rounded-full px-5 py-2 text-sm font-display font-semibold">Configure</a>
    </div>
  </div>
  @endforeach
</div>
@endsection
