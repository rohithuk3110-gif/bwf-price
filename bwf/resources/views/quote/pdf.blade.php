<!DOCTYPE html><html><head><meta charset="utf-8"><style>
body{font-family:DejaVu Sans,Helvetica,sans-serif;color:#232858;font-size:12px;margin:36px}
h1{color:#D3312C;font-size:20px;margin:0}.muted{color:#4A5068;font-size:10px}
table{width:100%;border-collapse:collapse;margin-top:14px}td{padding:5px 0;border-bottom:1px solid #eee}
.tot td{border-top:2px solid #232858;font-weight:bold;font-size:14px}
.ref{float:right;color:#D3312C;font-weight:bold}
</style></head><body>
@php $item = $quote->items->first(); $p = $item->product; @endphp
<span class="ref">{{ $quote->reference }}</span>
<h1>BRAMLEY WINDOW FACTORY</h1>
<div class="muted">Quotation · issued {{ $quote->created_at->format('j M Y') }} · valid until {{ $quote->valid_until->format('j M Y') }} · lead time {{ $quote->lead_time }}</div>
<h2 style="margin-top:18px">{{ $p->name }} × {{ $item->quantity }} — {{ $item->width_mm }} × {{ $item->height_mm }} mm</h2>
<div class="muted">{{ $p->description }}</div>
<div class="muted" style="margin-top:8px">Price build-up (ex VAT)</div><table>
@foreach ($item->breakdown as $l)<tr><td>{{ $l['label'] }}</td><td align="right">£{{ number_format($l['amount'], 2) }}</td></tr>@endforeach
<tr><td>Delivery (per order)</td><td align="right">£{{ number_format($quote->delivery, 2) }}</td></tr>
<tr><td>VAT</td><td align="right">£{{ number_format($quote->vat_amount, 2) }}</td></tr>
<tr class="tot"><td>Total inc VAT</td><td align="right">£{{ number_format($quote->grand_total, 2) }}</td></tr>
</table>
<p class="muted" style="margin-top:20px">All sizes are overall frame sizes including cill where selected. Manufactured to measure — please check dimensions carefully. Terms &amp; conditions apply.</p>
</body></html>
