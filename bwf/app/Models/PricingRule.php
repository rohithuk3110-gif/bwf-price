<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PricingRule extends Model
{
    protected $fillable = ['product_id','scope','code','label','component','method','value',
        'min_charge','max_charge','waste_factor','per_unit','condition_attr','condition_value',
        'condition_negate','is_placeholder','is_verified','valid_from','valid_to','priority','is_active'];
    protected $casts = ['value'=>'decimal:4','min_charge'=>'decimal:2','max_charge'=>'decimal:2',
        'waste_factor'=>'decimal:4','condition_negate'=>'boolean','is_placeholder'=>'boolean',
        'is_verified'=>'boolean','is_active'=>'boolean','valid_from'=>'date','valid_to'=>'date'];
    public function product() { return $this->belongsTo(Product::class); }
}
