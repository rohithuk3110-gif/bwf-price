<?php
namespace Database\Seeders;
use App\Models\PricingRule;
use App\Models\ValidationRule;
use Illuminate\Database\Seeder;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        $R = fn (array $a) => PricingRule::updateOrCreate(['code' => $a['code'], 'product_id' => null], $a);
        // ⚠ is_placeholder rules = example rates awaiting confirmed factory pricing (edit in Staff Portal)
        $R(['code'=>'SIZE','label'=>'Made-to-measure size adjustment','component'=>'BASE_ADJ','method'=>'PER_MM_OVER','value'=>0.055,'is_placeholder'=>true,'priority'=>10]);
        $R(['code'=>'COL_EXT','label'=>'Woodgrain / colour finish','component'=>'COLOUR','method'=>'PCT_BASE','value'=>0.18,'condition_attr'=>'ext_colour','condition_value'=>'white','condition_negate'=>true,'is_placeholder'=>true,'priority'=>20]);
        $R(['code'=>'COL_INT','label'=>'Dual-side colour finish','component'=>'COLOUR','method'=>'PCT_BASE','value'=>0.12,'condition_attr'=>'int_colour','condition_value'=>'match','is_placeholder'=>true,'priority'=>21]);
        $R(['code'=>'TOUGH','label'=>'Toughened safety glass','component'=>'GLASS','method'=>'PER_M2','value'=>14,'min_charge'=>12,'condition_attr'=>'toughened','condition_value'=>'yes','is_placeholder'=>true,'priority'=>30]);
        $R(['code'=>'FROST','label'=>'Obscure glass','component'=>'GLASS','method'=>'PER_M2','value'=>11,'min_charge'=>10,'condition_attr'=>'frosting','condition_value'=>'clear','condition_negate'=>true,'is_placeholder'=>true,'priority'=>31]);
        $R(['code'=>'U13','label'=>'1.3 U-value upgrade','component'=>'GLASS','method'=>'FIXED','value'=>12,'condition_attr'=>'uvalue','condition_value'=>'u13','is_placeholder'=>true,'priority'=>32]);
        $R(['code'=>'U11','label'=>'1.1 U-value upgrade','component'=>'GLASS','method'=>'FIXED','value'=>26,'condition_attr'=>'uvalue','condition_value'=>'u11','is_placeholder'=>true,'priority'=>33]);
        $R(['code'=>'CILL85','label'=>'85 mm stub cill','component'=>'OPTION','method'=>'FIXED','value'=>14,'condition_attr'=>'cill','condition_value'=>'c85','is_placeholder'=>true,'priority'=>40]);
        $R(['code'=>'CILL150','label'=>'150 mm cill','component'=>'OPTION','method'=>'FIXED','value'=>20,'condition_attr'=>'cill','condition_value'=>'c150','is_placeholder'=>true,'priority'=>41]);
        $R(['code'=>'CILL180','label'=>'180 mm cill','component'=>'OPTION','method'=>'FIXED','value'=>26,'condition_attr'=>'cill','condition_value'=>'c180','is_placeholder'=>true,'priority'=>42]);
        $R(['code'=>'HANDLE','label'=>'Premium handle finish','component'=>'HARDWARE','method'=>'FIXED','value'=>6,'per_unit'=>'OPENER','condition_attr'=>'handle','condition_value'=>'white','condition_negate'=>true,'is_placeholder'=>true,'priority'=>50]);
        $R(['code'=>'VENT','label'=>'Trickle vents','component'=>'HARDWARE','method'=>'FIXED','value'=>9,'per_unit'=>'LIGHT','condition_attr'=>'vent','condition_value'=>'yes','is_placeholder'=>true,'priority'=>51]);
        $R(['code'=>'GEORGIAN','label'=>'Georgian bars','component'=>'OPTION','method'=>'PER_BAR','value'=>8,'condition_attr'=>'georgian','condition_value'=>'yes','is_placeholder'=>true,'priority'=>60]);
        $R(['code'=>'ASTRAGAL','label'=>'Astragal bar detailing','component'=>'OPTION','method'=>'FIXED','value'=>45,'condition_attr'=>'astragal','condition_value'=>'yes','is_placeholder'=>true,'priority'=>61]);
        $R(['code'=>'HORNS','label'=>'Run-through sash horns','component'=>'OPTION','method'=>'FIXED','value'=>22,'condition_attr'=>'horns','condition_value'=>'yes','is_placeholder'=>true,'priority'=>62]);
        $R(['code'=>'LETTERPLATE','label'=>'Letter plate','component'=>'HARDWARE','method'=>'FIXED','value'=>18,'condition_attr'=>'letterplate','condition_value'=>'yes','is_placeholder'=>true,'priority'=>70]);
        $R(['code'=>'LOWTHRESH','label'=>'Low aluminium threshold','component'=>'HARDWARE','method'=>'FIXED','value'=>34,'condition_attr'=>'threshold','condition_value'=>'low','is_placeholder'=>true,'priority'=>71]);
        $R(['code'=>'DELIVERY','label'=>'UK mainland delivery (per order)','component'=>'DELIVERY','method'=>'FIXED','value'=>75,'is_verified'=>true,'priority'=>90]);

        $V = fn (array $a) => ValidationRule::updateOrCreate(['rule_type' => $a['rule_type'], 'attribute_code' => $a['attribute_code'] ?? null, 'product_id' => null, 'message' => $a['message']], $a);
        $V(['rule_type'=>'MIN','attribute_code'=>'width','value_number'=>400,'message'=>'Minimum width is 400 mm.']);
        $V(['rule_type'=>'MAX','attribute_code'=>'width','value_number'=>2400,'message'=>'Maximum width is 2400 mm.']);
        $V(['rule_type'=>'MIN','attribute_code'=>'height','value_number'=>400,'message'=>'Minimum height is 400 mm.']);
        $V(['rule_type'=>'MAX','attribute_code'=>'height','value_number'=>2200,'message'=>'Maximum height is 2200 mm.']);
        $V(['rule_type'=>'MAX_AREA','value_number'=>4.2,'severity'=>'WARNING','message'=>'Large frame — our surveyor will confirm before manufacture.']);
        $V(['rule_type'=>'FORCE','attribute_code'=>'below800','force_attribute'=>'toughened','force_value'=>'yes','severity'=>'INFO',
            'message'=>'Toughened safety glass has been included — required for glazing below 800 mm from floor level.']);
    }
}
