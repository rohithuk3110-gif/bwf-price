@extends('layouts.app')
@section('title', 'Staff login — Bramley Window Factory')
@section('content')
<form method="POST" action="{{ route('login') }}" class="max-w-sm mx-auto bg-white border border-[#E6E4DD] rounded-2xl p-6 shadow-sm">
  @csrf
  <h1 class="font-display font-bold text-lg mb-4">Staff Portal</h1>
  @error('email')<div class="mb-3 text-sm text-red-700">{{ $message }}</div>@enderror
  <label class="block text-[10px] font-semibold uppercase text-[#4A5068] mb-1">Email</label>
  <input name="email" type="email" required class="w-full border-2 border-[#E6E4DD] rounded-xl px-3 py-2 text-sm mb-3">
  <label class="block text-[10px] font-semibold uppercase text-[#4A5068] mb-1">Password</label>
  <input name="password" type="password" required class="w-full border-2 border-[#E6E4DD] rounded-xl px-3 py-2 text-sm mb-4">
  <button class="w-full bg-navy text-white rounded-full py-2.5 font-display font-semibold text-sm">Sign in</button>
</form>
@endsection
