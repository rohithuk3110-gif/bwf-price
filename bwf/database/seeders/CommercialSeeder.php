<?php
namespace Database\Seeders;
use App\Models\PriceList;
use App\Models\VatRule;
use App\Models\DeliveryRule;
use App\Models\DiscountRule;
use Illuminate\Database\Seeder;
class CommercialSeeder extends Seeder
{
    public function run(): void
    {
        PriceList::updateOrCreate(['code' => 'TRADE'],  ['label' => 'Trade',  'method' => 'MULT', 'factor' => 1.00]);
        PriceList::updateOrCreate(['code' => 'DEALER'], ['label' => 'Dealer', 'method' => 'MULT', 'factor' => 1.18]);
        PriceList::updateOrCreate(['code' => 'RETAIL'], ['label' => 'Retail', 'method' => 'MULT', 'factor' => 1.45, 'is_default' => true]);
        VatRule::updateOrCreate(['code' => 'STANDARD'], ['label' => 'Standard 20%', 'rate' => 0.20, 'is_default' => true]);
        VatRule::updateOrCreate(['code' => 'NEWBUILD'], ['label' => 'Qualifying new-build 0%', 'rate' => 0.00]);
        DeliveryRule::updateOrCreate(['label' => 'UK mainland delivery (per order)'], ['method' => 'PER_ORDER', 'amount' => 75.00]);
        DiscountRule::updateOrCreate(['code' => 'NONE'], ['label' => 'No discount', 'percent' => 0]);
    }
}
