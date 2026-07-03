<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Bramley Window Factory')</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{navy:'#232858',brand:'#D3312C',cream:'#F6F1E7'},fontFamily:{display:['Poppins','sans-serif'],body:['Inter','sans-serif']}}}}</script>
<style>body{font-family:Inter,sans-serif}</style>
</head>
<body class="bg-[#FAF9F6] text-navy">
<header class="bg-white border-b border-[#E6E4DD]">
  <div class="max-w-5xl mx-auto px-4 py-3 flex items-center gap-3">
    <a href="{{ route('home') }}" class="flex items-center gap-2">
      <span class="w-8 h-8 bg-brand rounded grid place-items-center"><span class="w-3.5 h-3.5 border-[3px] border-white rounded-sm"></span></span>
      <span><span class="block font-display font-extrabold text-brand tracking-wide leading-none">BRAMLEY</span>
      <span class="block text-[8px] tracking-[.3em] font-display text-navy">WINDOW FACTORY</span></span>
    </a>
    <nav class="ml-auto flex gap-4 text-sm font-display font-semibold">
      @foreach (\App\Models\Category::whereNull('parent_id')->where('is_active',true)->orderBy('sort_order')->get() as $top)
        <a class="hover:text-brand" href="{{ route('category', $top) }}">{{ $top->name }}</a>
      @endforeach
    </nav>
  </div>
</header>
<main class="max-w-5xl mx-auto px-4 py-6">
  @if (session('ok'))<div class="mb-4 rounded-xl bg-green-50 border border-green-600 text-green-800 px-4 py-2 text-sm">{{ session('ok') }}</div>@endif
  @yield('content')
</main>
<footer class="bg-navy text-[#C8CBE0] text-xs mt-10">
  <div class="max-w-5xl mx-auto px-4 py-5 flex justify-between flex-wrap gap-2">
    <span>© {{ date('Y') }} Bramley Window Factory — made-to-measure windows &amp; doors</span>
    <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}" class="text-[#8A90B8] hover:text-white">Staff Portal</a>
  </div>
</footer>
</body>
</html>
