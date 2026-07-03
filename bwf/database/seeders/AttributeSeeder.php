<?php
namespace Database\Seeders;
use App\Models\AttributeGroup;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // ----- uPVC Casement family (verified option set from AWM reference) -----
        $g = AttributeGroup::updateOrCreate(['name' => 'uPVC Casement Window']);
        $this->attrs($g, [
            ['width','Width (mm)','number','1200'],
            ['height','Height (mm)','number','1200'],
            ['ext_colour','External colour','swatch','white', [
                ['white','White','#F4F4F2',14],['anthracite','Anthracite Grey','#3B4245',21],
                ['golden_oak','Golden Oak','#8A5A2B',21],['rosewood','Rosewood','#4E2A23',21],
                ['agate','Agate Grey','#B5B0A1',38],['black','Black','#1B1B1B',21],
                ['irish_oak','Irish Oak','#A97C50',38],['chartwell','Chartwell Green','#B9CDB4',21]]],
            ['int_colour','Internal colour','select','white', [['white','White'],['match','Same as external']]],
            ['below800','Glazing below 800 mm from floor?','bool','no'],
            ['toughened','Toughened safety glass','bool','no'],
            ['frosting','Glass style','select','clear', [
                ['clear','Clear'],['stippolyte','Stippolyte'],['warwick','Warwick'],['chantilly','Chantilly'],
                ['reeded','Reeded'],['digital','Digital'],['taffeta','Taffeta'],['oak','Oak'],
                ['contora','Contora'],['charcoal','Charcoal Sticks'],['florielle','Florielle'],
                ['mayflower','Mayflower'],['pelerine','Pelerine'],['everglade','Everglade'],
                ['cassini','Cassini'],['tribal','Tribal'],['satin','Satin']]],
            ['uvalue','Thermal rating','select','u15', [['u15','1.5 U-value'],['u13','1.3 U-value'],['u11','1.1 U-value']]],
            ['cill','Cill','select','c150', [['none','No cill'],['c85','85 mm stub cill'],['c150','150 mm cill'],['c180','180 mm cill']]],
            ['handle','Handle finish','select','white', [['white','White'],['chrome','Chrome'],['satin','Satin Silver'],['gold','Gold'],['black','Black']]],
            ['vent','Trickle vents','bool','no'],
            ['georgian','Georgian bars','bool','no'],
            ['geo_h','Horizontal bars','number','1','','georgian','yes'],
            ['geo_v','Vertical bars','number','1','','georgian','yes'],
            ['qty','Quantity','number','1'],
        ]);

        // ----- Sliding Sash family -----
        $s = AttributeGroup::updateOrCreate(['name' => 'Sliding Sash Window']);
        $this->attrs($s, [
            ['width','Width (mm)','number','900'], ['height','Height (mm)','number','1400'],
            ['ext_colour','External colour','swatch','white', [
                ['white','White','#F4F4F2',14],['anthracite','Anthracite Grey','#3B4245',21],
                ['agate','Agate Grey','#B5B0A1',21],['black_brown','Black/Brown','#241A15',21],
                ['chartwell','Chartwell Green','#B9CDB4',21],['rosewood','Rosewood','#4E2A23',21],
                ['golden_oak','Golden Oak','#8A5A2B',21],['irish_oak','Irish Oak','#A97C50',21]]],
            ['below800','Glazing below 800 mm from floor?','bool','no'],
            ['toughened','Toughened safety glass','bool','no'],
            ['frosting','Glass style','select','clear', [['clear','Clear'],['stippolyte','Stippolyte'],['satin','Satin']]],
            ['astragal','Astragal bars','bool','no'], ['horns','Run-through sash horns','bool','no'],
            ['cill','Cill','select','c150', [['none','No cill'],['c150','150 mm cill'],['c180','180 mm cill']]],
            ['qty','Quantity','number','1'],
        ]);

        // ----- Door family (composite / french / patio / bifold / upvc) -----
        $d = AttributeGroup::updateOrCreate(['name' => 'Door']);
        $this->attrs($d, [
            ['width','Width (mm)','number','1800'], ['height','Height (mm)','number','2090'],
            ['ext_colour','External colour','swatch','white', [
                ['white','White','#F4F4F2',14],['anthracite','Anthracite Grey','#3B4245',21],
                ['black','Black','#1B1B1B',21],['rosewood','Rosewood','#4E2A23',21],
                ['golden_oak','Golden Oak','#8A5A2B',21],['chartwell','Chartwell Green','#B9CDB4',21]]],
            ['int_colour','Internal colour','select','white', [['white','White'],['match','Same as external']]],
            ['frosting','Glass style','select','clear', [['clear','Clear'],['stippolyte','Stippolyte'],['satin','Satin']]],
            ['uvalue','Thermal rating','select','u15', [['u15','1.5 U-value'],['u13','1.3 U-value']]],
            ['cill','Cill','select','c150', [['none','No cill'],['c150','150 mm cill'],['c180','180 mm cill']]],
            ['handle','Handle finish','select','white', [['white','White'],['chrome','Chrome'],['satin','Satin Silver'],['gold','Gold'],['black','Black']]],
            ['threshold','Threshold','select','std', [['std','Standard'],['low','Low aluminium']]],
            ['letterplate','Letter plate','bool','no'],
            ['qty','Quantity','number','1'],
        ]);
    }

    private function attrs(AttributeGroup $g, array $rows): void
    {
        foreach ($rows as $i => $r) {
            $a = $g->attributes()->updateOrCreate(['code' => $r[0]], [
                'label' => $r[1], 'input_type' => $r[2], 'default_value' => $r[3],
                'parent_code' => $r[5] ?? null, 'parent_trigger' => $r[6] ?? null, 'sort_order' => $i,
            ]);
            foreach (($r[4] ?? []) as $j => $o) {
                $a->options()->updateOrCreate(['code' => $o[0]], [
                    'label' => $o[1], 'swatch_hex' => $o[2] ?? null,
                    'lead_time_days' => $o[3] ?? null, 'sort_order' => $j,
                ]);
            }
        }
    }
}
