@php
  $cols=$p->layout_cols; $rows=$p->layout_rows; $op=$p->opener_cells??[];
  $W=150;$H=123;$f=7;$m=5; $cw=($W-2*$f-($cols-1)*$m)/$cols; $ch=($H-2*$f-($rows-1)*$m)/$rows;
  $fill=$fill??'#F4F4F2';
@endphp
<svg viewBox="0 0 {{ $W }} {{ $H }}" class="w-full max-w-[220px] mx-auto block">
<rect x=".8" y=".8" width="{{ $W-1.6 }}" height="{{ $H-1.6 }}" fill="{{ $fill }}" stroke="#232858" stroke-width="1.4" rx="2"/>
@for ($r=0;$r<$rows;$r++)@for ($c=0;$c<$cols;$c++)
@php $i=$r*$cols+$c; $x=$f+$c*($cw+$m); $y=$f+$r*($ch+$m); @endphp
<rect x="{{ $x }}" y="{{ $y }}" width="{{ $cw }}" height="{{ $ch }}" fill="#DCE9F2" stroke="#232858" stroke-width=".9"/>
<line x1="{{ $x+$cw*.18 }}" y1="{{ $y+$ch*.6 }}" x2="{{ $x+$cw*.5 }}" y2="{{ $y+$ch*.12 }}" stroke="#fff" stroke-width="1.6" opacity=".6"/>
@if (in_array($i,$op))<path d="M {{ $x+$cw }} {{ $y }} L {{ $x }} {{ $y+$ch/2 }} L {{ $x+$cw }} {{ $y+$ch }}" stroke="#D3312C" stroke-width="1.2" stroke-dasharray="4 3" fill="none"/>@endif
@endfor @endfor
</svg>
