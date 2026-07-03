@extends('layouts.app')
@section('title', $product->name.' — configure & price — Bramley Window Factory')
@section('content')
<nav class="text-xs text-[#4A5068] mb-3">
  <a class="font-semibold text-navy" href="{{ route('home') }}">Home</a> ›
  <a class="font-semibold text-navy" href="{{ route('category', $product->category->parent) }}">{{ $product->category->parent->name }}</a> ›
  <a class="font-semibold text-navy" href="{{ route('category', $product->category) }}">{{ $product->category->name }}</a> › {{ $product->name }}
</nav>
<div class="grid gap-5 lg:grid-cols-[300px_1fr]">
  <div class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm self-start">
    @include('shop._diagram', ['p' => $product])
    <h1 class="font-display font-bold text-xl mt-3">{{ $product->name }}</h1>
    <p class="text-sm text-[#4A5068] mt-1">{{ $product->description }}</p>
    <div id="pricePanel" class="bg-cream rounded-xl px-4 py-3 mt-4 hidden">
      <div class="text-[10px] text-[#4A5068] tracking-wide">YOUR PRICE · INC VAT · <span id="lead"></span></div>
      <div id="grand" class="font-display font-extrabold text-2xl"></div>
      <div id="perUnit" class="text-xs text-[#4A5068]"></div>
    </div>
  </div>
  <div>
    <div id="msgs"></div>
    <form id="cfgForm" method="POST" action="{{ route('quote.store', $product) }}" class="bg-white border border-[#E6E4DD] rounded-2xl p-5 shadow-sm">
      @csrf
      <h2 class="font-display font-bold mb-4">Configure your {{ $product->name }}</h2>
      <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($product->attributeGroup->attributes as $a)
        <div data-attr="{{ $a->code }}" @if($a->parent_code) data-parent="{{ $a->parent_code }}" data-trigger="{{ $a->parent_trigger }}" @endif>
          <label class="block text-[10px] font-semibold tracking-wide uppercase text-[#4A5068] mb-1">{{ $a->label }}</label>
          @if ($a->input_type === 'number')
            <input type="number" name="config[{{ $a->code }}]" value="{{ $a->default_value }}"
              class="w-full border-2 border-[#E6E4DD] rounded-xl px-3 py-2 text-sm cfg">
          @elseif ($a->input_type === 'bool')
            <select name="config[{{ $a->code }}]" class="w-full border-2 border-[#E6E4DD] rounded-xl px-3 py-2 text-sm cfg">
              <option value="no" @selected($a->default_value==='no')>No</option>
              <option value="yes" @selected($a->default_value==='yes')>Yes</option>
            </select>
          @elseif ($a->input_type === 'swatch')
            <div class="flex flex-wrap gap-1.5">
              @foreach ($a->options as $o)
              <label class="cursor-pointer">
                <input type="radio" name="config[{{ $a->code }}]" value="{{ $o->code }}" class="peer sr-only cfg" @checked($o->code === $a->default_value)>
                <span title="{{ $o->label }}" class="block w-8 h-8 rounded-lg border-2 border-[#E6E4DD] peer-checked:border-brand peer-checked:border-[3px]" style="background: {{ $o->swatch_hex }}"></span>
              </label>
              @endforeach
            </div>
          @else
            <select name="config[{{ $a->code }}]" class="w-full border-2 border-[#E6E4DD] rounded-xl px-3 py-2 text-sm cfg">
              @foreach ($a->options as $o)
              <option value="{{ $o->code }}" @selected($o->code === $a->default_value)>{{ $o->label }}@if(($o->lead_time_days ?? 0) >= 35) — extended lead time @endif</option>
              @endforeach
            </select>
          @endif
        </div>
        @endforeach
      </div>
      <div id="breakdown" class="mt-5 hidden">
        <h3 class="font-display font-bold text-sm mb-2">What's included in your price</h3>
        <div id="lines" class="text-sm"></div>
      </div>
      <button id="quoteBtn" type="submit" class="mt-5 w-full bg-brand text-white rounded-full py-3 font-display font-semibold disabled:opacity-40" disabled>Get my quote</button>
    </form>
  </div>
</div>
<script>
const form = document.getElementById('cfgForm');
const csrf = document.querySelector('input[name=_token]').value;
function collect() {
  const cfg = {};
  new FormData(form).forEach((v, k) => { const m = k.match(/^config\[(.+)\]$/); if (m) cfg[m[1]] = v; });
  return cfg;
}
function conditionals() {
  document.querySelectorAll('[data-parent]').forEach(el => {
    const parent = form.querySelector(`[name="config[${el.dataset.parent}]"]`);
    el.style.display = (parent && parent.value === el.dataset.trigger) ? '' : 'none';
  });
}
let t;
async function reprice() {
  conditionals();
  const res = await fetch(@json(route('price', $product)), {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    body: JSON.stringify({ config: collect() })
  });
  const d = await res.json();
  const msgs = document.getElementById('msgs'); msgs.innerHTML = '';
  (d.errors || []).forEach(e => msgs.innerHTML += `<div class="mb-2 rounded-xl bg-red-50 border border-red-600 text-red-700 px-4 py-2 text-sm">${e}</div>`);
  (d.notes || []).forEach(n => msgs.innerHTML += `<div class="mb-2 rounded-xl bg-cream text-navy px-4 py-2 text-sm">${n}</div>`);
  const panel = document.getElementById('pricePanel'), bd = document.getElementById('breakdown'), btn = document.getElementById('quoteBtn');
  if (!d.ok) { panel.classList.add('hidden'); bd.classList.add('hidden'); btn.disabled = true; btn.textContent = 'Fix the details above to see your price'; return; }
  panel.classList.remove('hidden'); bd.classList.remove('hidden'); btn.disabled = false;
  document.getElementById('grand').textContent = '£' + d.grand_total.toLocaleString('en-GB', { minimumFractionDigits: 2 });
  document.getElementById('lead').textContent = 'LEAD TIME ' + d.lead_time.toUpperCase();
  document.getElementById('perUnit').textContent = `${d.quantity} × £${(d.unit_price * (1 + d.vat_rate)).toFixed(2)} inc VAT + delivery £${(d.delivery * (1 + d.vat_rate)).toFixed(2)}`;
  document.getElementById('lines').innerHTML = d.lines.map(l =>
    `<div class="flex justify-between border-b border-[#FAF9F6] py-1.5"><span>${l.label}</span><span class="font-semibold">£${(l.amount * (1 + d.vat_rate)).toFixed(2)}</span></div>`).join('');
  btn.textContent = 'Get my quote — £' + d.grand_total.toLocaleString('en-GB', { minimumFractionDigits: 2 });
}
form.addEventListener('input', () => { clearTimeout(t); t = setTimeout(reprice, 300); });
reprice();
</script>
@endsection
