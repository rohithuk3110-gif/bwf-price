@extends('layouts.app')
@section('title', 'Bramley Window Factory — made-to-measure windows & doors, priced instantly')
@section('content')
<div class="bg-navy rounded-3xl p-8 text-white mb-6">
  <h1 class="font-display font-extrabold text-3xl leading-tight">Made-to-measure windows &amp; doors,<br><span class="text-[#F0B8B6]">priced instantly.</span></h1>
  <p class="text-[#C8CBE0] text-sm mt-3 max-w-lg">Choose your exact design from our catalogue, configure it to your sizes and finishes, and see your price live — just like ordering at our factory counter.</p>
  <a href="{{ route('category', 'windows') }}" class="inline-block mt-5 bg-brand rounded-full px-6 py-2.5 font-display font-semibold text-sm">Browse windows</a>
</div>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  @foreach ($tops as $t)
  <a href="{{ route('category', $t) }}" class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm hover:shadow transition">
    <div class="font-display font-bold text-lg">{{ $t->name }}</div>
    <div class="text-sm text-[#4A5068] mt-1">{{ $t->children->count() }} ranges</div>
    <div class="text-brand font-display font-semibold text-sm mt-3">Explore →</div>
  </a>
  @endforeach
</div>
@endsection
