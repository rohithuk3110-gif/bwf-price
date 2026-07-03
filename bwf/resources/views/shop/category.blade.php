@extends('layouts.app')
@section('title', $cat->name.' — Bramley Window Factory')
@section('content')
<nav class="text-xs text-[#4A5068] mb-3"><a class="font-semibold text-navy" href="{{ route('home') }}">Home</a> › {{ $cat->name }}</nav>
<h1 class="font-display font-bold text-2xl">{{ $cat->name }}</h1>
<p class="text-sm text-[#4A5068] mt-1 mb-5">{{ $cat->blurb }}</p>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
  @foreach ($cat->children->where('is_active', true) as $s)
  <div class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm">
    <div class="font-display font-bold">{{ $s->name }}</div>
    <div class="text-sm text-[#4A5068] mt-1 mb-4">{{ $s->blurb }}</div>
    @if ($s->products->where('is_active', true)->count())
      <a href="{{ route('category', $s) }}" class="inline-block bg-navy text-white rounded-full px-5 py-2 text-sm font-display font-semibold">View {{ $s->products->where('is_active', true)->count() }} designs</a>
    @else
      <span class="inline-block bg-cream rounded-full px-4 py-1.5 text-xs font-display font-semibold text-[#4A5068]">Range coming online</span>
    @endif
  </div>
  @endforeach
</div>
@endsection
