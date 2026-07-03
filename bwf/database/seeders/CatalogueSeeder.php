<?php
namespace Database\Seeders;
use App\Models\AttributeGroup;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $cas  = AttributeGroup::where('name', 'uPVC Casement Window')->first();
        $sash = AttributeGroup::where('name', 'Sliding Sash Window')->first();
        $door = AttributeGroup::where('name', 'Door')->first();

        // top-level categories (Bramley navigation)
        $windows = Category::updateOrCreate(['slug' => 'windows'], ['name' => 'Windows', 'sort_order' => 1, 'blurb' => 'Made-to-measure uPVC and aluminium windows.']);
        $doors   = Category::updateOrCreate(['slug' => 'doors'],   ['name' => 'Doors', 'sort_order' => 2, 'blurb' => 'Composite, French, patio, bi-fold and uPVC doors.']);
        $cons    = Category::updateOrCreate(['slug' => 'conservatories'], ['name' => 'Conservatories', 'sort_order' => 3, 'blurb' => 'Apex, Edwardian, Victorian, Lean-To, P and T shapes.']);
        $glass   = Category::updateOrCreate(['slug' => 'glass'],   ['name' => 'Glass', 'sort_order' => 4, 'blurb' => 'Double, triple, EcoMAX and decorative glazing.']);

        $sub = fn ($parent, $slug, $name, $blurb, $i) => Category::updateOrCreate(['slug' => $slug],
            ['parent_id' => $parent->id, 'name' => $name, 'blurb' => $blurb, 'sort_order' => $i]);

        $casementCat = $sub($windows, 'casement-windows', 'Casement Windows', 'Sculptured uPVC casements — our full range of designs.', 1);
        $sashCat     = $sub($windows, 'sliding-sash-windows', 'Sliding Sash Windows', 'Traditional vertical sliders with modern performance.', 2);
        $ttCat       = $sub($windows, 'tilt-turn-windows', 'Tilt & Turn Windows', 'Inward tilt for ventilation, full turn for cleaning.', 3);
        $sub($windows, 'flush-casement-windows', 'Flush Casement Windows', 'Timber-look flush exterior.', 4);
        $sub($windows, 'aluminium-windows', 'Aluminium Windows', 'Slim-sightline aluminium.', 5);
        $compCat   = $sub($doors, 'composite-doors', 'Composite Doors', 'High-security GRP composite doors.', 1);
        $frenchCat = $sub($doors, 'french-doors', 'French Doors', 'Classic pairs with panel and light options.', 2);
        $patioCat  = $sub($doors, 'sliding-patio-doors', 'Sliding Patio Doors', 'Smooth inline sliders, 2-4 panes.', 3);
        $bifoldCat = $sub($doors, 'bi-fold-doors', 'Bi-Fold Doors', 'Full-width concertina openings.', 4);
        $upvcCat   = $sub($doors, 'upvc-doors', 'uPVC Doors', 'Front, back and stable doors.', 5);
        foreach ([['apex','Apex'],['edwardian','Edwardian'],['victorian','Victorian'],['lean-to','Lean-To'],['p-shape','P Shape'],['t-shape','T Shape']] as $i => $c)
            $sub($cons, $c[0], $c[1], $c[1].' conservatory range.', $i + 1);
        foreach ([['double-glazing','Double Glazing'],['triple-glazing','Triple Glazing'],['ecomax-glazing','EcoMAX Glazing'],['decorative-glass','Decorative Glass']] as $i => $c)
            $sub($glass, $c[0], $c[1], $c[1].'.', $i + 1);

        // ---- 58 casement products; 1-31 carry VERIFIED base prices (ex-VAT trade) ----
        $verified = [120,155,165,165,140.4,176.4,176.4,211.2,182.4,182.4,230.4,230.4,204,204,234,204,204,213.6,271.2,308.4,351.6,315.6,316.9,297.6,297.6,298.6,297.6,316.9,315.6,338.4,422.4];
        $layouts = [[1,1,[0]],[2,1,[1]],[2,1,[0]],[2,1,[0,1]],[3,1,[1]],[3,1,[0,2]],[3,1,[0,1,2]],[2,2,[1]],[3,1,[2]],[2,1,[]],[3,2,[1]],[2,2,[0,1]],[3,1,[0]],[4,1,[1,2]],[3,2,[0,2]],[4,1,[0,3]],[2,2,[0,3]],[3,2,[1,4]],[4,1,[0]],[3,1,[1,2]],[4,2,[1,2]],[3,2,[0,1,2]],[4,1,[0,1,2,3]],[2,2,[2,3]],[3,2,[3,5]],[4,2,[0,3]],[3,2,[0,5]],[4,2,[0,1]],[4,2,[4,7]],[3,2,[2,3]],[4,2,[0,1,2,3]]];
        for ($i = 1; $i <= 58; $i++) {
            $L = $layouts[($i - 1) % count($layouts)];
            $isVerified = $i <= 31;
            Product::updateOrCreate(['sku' => 'CAS-'.str_pad($i, 2, '0', STR_PAD_LEFT)], [
                'category_id' => $casementCat->id, 'attribute_group_id' => $cas->id,
                'slug' => 'casement-'.str_pad($i, 2, '0', STR_PAD_LEFT),
                'name' => 'Casement '.$i,
                'description' => ($L[0] * $L[1]).'-light sculptured uPVC casement window with '.(count($L[2]) ?: 'no').' opening sash'.(count($L[2]) === 1 ? '' : 'es').'. Made to measure in our own factory.',
                'layout_cols' => $L[0], 'layout_rows' => $L[1], 'opener_cells' => $L[2],
                'base_price' => $isVerified ? round($verified[$i - 1] / 1.2, 2) : round(95 + $L[0] * $L[1] * 38 + count($L[2]) * 26, 2),
                'price_verified' => $isVerified, 'sort_order' => $i,
            ]);
        }

        $mk = function ($cat, $group, $prefix, $rows) {
            foreach ($rows as $i => [$name, $desc, $price, $cols, $rowsN, $openers]) {
                Product::updateOrCreate(['sku' => strtoupper($prefix).'-'.($i + 1)], [
                    'category_id' => $cat->id, 'attribute_group_id' => $group->id,
                    'slug' => $prefix.'-'.($i + 1), 'name' => $name, 'description' => $desc,
                    'layout_cols' => $cols, 'layout_rows' => $rowsN, 'opener_cells' => $openers,
                    'base_price' => $price, 'price_verified' => false, 'sort_order' => $i + 1,
                ]);
            }
        };
        $mk($sashCat, $sash, 'sash', [
            ['Sliding Sash 1', '1-over-1 traditional box sash.', 407.53, 1, 2, [0, 1]],
            ['Sliding Sash 2', '2-over-2 with astragal bars.', 445, 1, 2, [0, 1]],
            ['Sliding Sash 3', '6-over-6 Georgian arrangement.', 489, 1, 2, [0, 1]],
            ['Sliding Sash 4', 'Arched-head sash.', 539, 1, 2, [0, 1]],
        ]);
        $mk($ttCat, $cas, 'tilt-turn', [
            ['Tilt & Turn 1', 'Single tilt-turn opener.', 196, 1, 1, [0]],
            ['Tilt & Turn 2', 'Twin — one tilt-turn, one fixed.', 278, 2, 1, [0]],
            ['Tilt & Turn 3', 'Twin tilt-turn.', 332, 2, 1, [0, 1]],
            ['Tilt & Turn 4', 'Triple with centre fixed light.', 398, 3, 1, [0, 2]],
        ]);
        $mk($frenchCat, $door, 'french', [
            ['French Door Classic', 'Standard French pair.', 529, 2, 1, [0, 1]],
            ['French Door + Side Panels', 'Pair with two fixed sidelights.', 689, 4, 1, [1, 2]],
            ['French Door + Top Light', 'Pair with fixed top light.', 619, 2, 1, [0, 1]],
            ['French Door + Midrail', 'Pair with solid midrail.', 579, 2, 1, [0, 1]],
            ['Offset French Door', 'Unequal-leaf pair.', 559, 2, 1, [0, 1]],
        ]);
        $mk($compCat, $door, 'composite', [
            ['Composite Rustic', 'Traditional cottage-style slab.', 624, 1, 1, [0]],
            ['Composite Contemporary', 'Flush modern slab.', 648, 1, 1, [0]],
            ['Composite Glazed Duo', 'Twin vertical glazing.', 672, 1, 1, [0]],
            ['Composite Solid', 'Full solid security slab.', 598, 1, 1, [0]],
        ]);
        $mk($patioCat, $door, 'patio', [
            ['Patio 2-Pane uPVC', 'Two-pane inline slider.', 729, 2, 1, [0]],
            ['Patio 3-Pane uPVC', 'Three-pane slider.', 939, 3, 1, [0]],
            ['Patio 4-Pane uPVC', 'Four-pane slider.', 1149, 4, 1, [0]],
        ]);
        $mk($bifoldCat, $door, 'bifold', [
            ['Bi-Fold 3+0', 'Three leaves folding one direction.', 1290, 3, 1, [0, 1, 2]],
            ['Bi-Fold 2+1', 'Two leaves plus traffic door.', 1350, 3, 1, [0, 1, 2]],
        ]);
        $mk($upvcCat, $door, 'upvc-door', [
            ['uPVC Door Half Glazed', 'Half panel, half glass.', 398, 1, 1, [0]],
            ['uPVC Door Full Glazed', 'Full glass leaf.', 412, 1, 1, [0]],
            ['uPVC Stable Door', 'Independent top and bottom leaf.', 585, 1, 1, [0]],
        ]);
    }
}
