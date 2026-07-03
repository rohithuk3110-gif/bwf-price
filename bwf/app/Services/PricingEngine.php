<?php
namespace App\Services;

use App\Models\Product;
use App\Models\PricingRule;
use App\Models\ValidationRule;
use App\Models\PriceList;
use App\Models\VatRule;
use App\Models\DeliveryRule;

/**
 * Generic, fully database-driven pricing engine.
 * Rules resolve through the scope chain GLOBAL -> FAMILY(attribute group) -> PRODUCT;
 * a product-scoped rule with the same code overrides the family/global rule.
 * Nothing in this class contains a price. Every figure is a database row.
 */
class PricingEngine
{
    public function price(Product $product, array $cfg, string $priceListCode = 'RETAIL', string $vatCode = 'STANDARD'): array
    {
        $errors = []; $notes = [];
        $cfg = $this->applyDefaults($product, $cfg);

        // ---- validation & forced options (data-driven) ----
        $vrules = ValidationRule::where('is_active', true)
            ->where(function ($q) use ($product) {
                $q->whereNull('product_id')->orWhere('product_id', $product->id);
            })->get();

        foreach ($vrules as $v) {
            if ($v->rule_type === 'FORCE' && ($cfg[$v->attribute_code] ?? '') === 'yes') {
                if (($cfg[$v->force_attribute] ?? '') !== $v->force_value) {
                    $cfg[$v->force_attribute] = $v->force_value;
                    $notes[] = $v->message;
                }
            }
            if ($v->rule_type === 'MIN' && isset($cfg[$v->attribute_code]) && (float)$cfg[$v->attribute_code] < (float)$v->value_number) {
                $errors[] = $v->message;
            }
            if ($v->rule_type === 'MAX' && isset($cfg[$v->attribute_code]) && (float)$cfg[$v->attribute_code] > (float)$v->value_number) {
                $errors[] = $v->message;
            }
            if ($v->rule_type === 'MAX_AREA') {
                $area = ((float)($cfg['width'] ?? 0) * (float)($cfg['height'] ?? 0)) / 1e6;
                if ($area > (float)$v->value_number) {
                    if ($v->severity === 'ERROR') $errors[] = $v->message; else $notes[] = $v->message;
                }
            }
        }
        $qty = (int)($cfg['qty'] ?? 1);
        if ($qty < 1) $errors[] = 'Quantity must be at least 1.';
        if ($errors) return ['ok' => false, 'errors' => $errors, 'notes' => $notes];

        // ---- resolve rules: global + family, overridden by product-scoped ----
        $rules = PricingRule::where('is_active', true)
            ->where(function ($q) use ($product) {
                $q->whereNull('product_id')->orWhere('product_id', $product->id);
            })
            ->where(function ($q) { $q->whereNull('valid_from')->orWhere('valid_from', '<=', now()); })
            ->where(function ($q) { $q->whereNull('valid_to')->orWhere('valid_to', '>=', now()); })
            ->orderBy('priority')->get()
            ->groupBy('code')
            ->map(fn ($g) => $g->sortByDesc('product_id')->first()); // product override wins

        $width = (float)($cfg['width'] ?? 0);
        $height = (float)($cfg['height'] ?? 0);
        $areaM2 = $width * $height / 1e6;
        $openers = count($product->opener_cells ?? []);
        $lights = max(1, (int)$product->layout_cols * (int)$product->layout_rows);

        $lines = [];
        $add = function (string $label, float $amt, bool $ph = false) use (&$lines) {
            if ($amt > 0.004) $lines[] = ['label' => $label, 'amount' => round($amt, 2), 'placeholder' => $ph];
        };

        // BASE — the product's own record
        $base = (float)$product->base_price;
        $add($product->name.' — made to your size', $base, !$product->price_verified);

        // generic rule evaluation
        foreach ($rules as $r) {
            if ($r->component === 'BASE' || $r->component === 'DELIVERY') continue;
            if (!$this->conditionMet($r, $cfg)) continue;
            $units = $r->per_unit === 'OPENER' ? max(1, $openers) : ($r->per_unit === 'LIGHT' ? $lights : 1);
            $amt = match ($r->method) {
                'FIXED'        => (float)$r->value * $units,
                'PER_M2'       => max($areaM2 * (float)$r->value, (float)($r->min_charge ?? 0)),
                'PCT_BASE'     => $base * (float)$r->value,
                'PER_MM_OVER'  => (max(0, $width - 1200) + max(0, $height - 1200)) * (float)$r->value,
                'PER_BAR'      => ((int)($cfg['geo_h'] ?? 0) + (int)($cfg['geo_v'] ?? 0)) * (float)$r->value * $lights,
                default        => 0.0,
            };
            $amt *= (1 + (float)($r->waste_factor ?? 0));
            if ($r->max_charge) $amt = min($amt, (float)$r->max_charge);
            if ($r->method === 'PER_MM_OVER') $base += $amt; // size adj feeds colour %
            $add($r->label, $amt, (bool)$r->is_placeholder);
        }

        $costSubtotal = array_sum(array_column($lines, 'amount'));

        // price list (markup or margin, from DB)
        $pl = PriceList::where('code', $priceListCode)->first() ?? PriceList::where('is_default', true)->first();
        $unit = $pl->method === 'MARGIN'
            ? $costSubtotal / max(0.01, 1 - (float)$pl->factor)
            : $costSubtotal * (float)$pl->factor;

        // scale breakdown lines to the selected price list so displayed lines sum to the unit price
        $scale = $costSubtotal > 0 ? $unit / $costSubtotal : 1;
        $lines = array_map(function ($l) use ($scale) { $l['amount'] = round($l['amount'] * $scale, 2); return $l; }, $lines);

        $itemsTotal = $unit * $qty;
        $delivery = (float) (DeliveryRule::where('is_active', true)->value('amount') ?? 0);
        $vat = VatRule::where('code', $vatCode)->first() ?? VatRule::where('is_default', true)->first();
        $exVat = $itemsTotal + $delivery;
        $vatAmount = $exVat * (float)$vat->rate;

        // lead time from selected option rows
        $lead = 14;
        foreach ($product->attributeGroup->attributes as $a) {
            $sel = $cfg[$a->code] ?? null;
            if ($sel) {
                $opt = $a->options->firstWhere('code', $sel);
                if ($opt && $opt->lead_time_days) $lead = max($lead, (int)$opt->lead_time_days);
            }
        }

        return [
            'ok' => true, 'errors' => [], 'notes' => $notes, 'config' => $cfg,
            'lines' => $lines, 'unit_cost' => round($costSubtotal, 2),
            'unit_price' => round($unit, 2), 'quantity' => $qty,
            'items_total' => round($itemsTotal, 2), 'delivery' => round($delivery, 2),
            'ex_vat' => round($exVat, 2), 'vat_rate' => (float)$vat->rate,
            'vat_amount' => round($vatAmount, 2), 'grand_total' => round($exVat + $vatAmount, 2),
            'lead_time' => $lead <= 21 ? '2-3 weeks' : '5-6 weeks',
        ];
    }

    private function applyDefaults(Product $product, array $cfg): array
    {
        foreach ($product->attributeGroup->attributes as $a) {
            if (!array_key_exists($a->code, $cfg) || $cfg[$a->code] === '' || $cfg[$a->code] === null) {
                $cfg[$a->code] = $a->default_value;
            }
        }
        return $cfg;
    }

    private function conditionMet(PricingRule $r, array $cfg): bool
    {
        if (!$r->condition_attr) return true;
        $cur = (string)($cfg[$r->condition_attr] ?? '');
        return $r->condition_negate ? $cur !== (string)$r->condition_value : $cur === (string)$r->condition_value;
    }
}
