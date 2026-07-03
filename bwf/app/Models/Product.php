<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id','attribute_group_id','sku','slug','name','description',
        'layout_cols','layout_rows','opener_cells','base_price','price_verified','sort_order','is_active'];
    protected $casts = ['opener_cells' => 'array', 'base_price' => 'decimal:2', 'price_verified' => 'boolean', 'is_active' => 'boolean'];
    public function category() { return $this->belongsTo(Category::class); }
    public function attributeGroup() { return $this->belongsTo(AttributeGroup::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function pricingRules() { return $this->hasMany(PricingRule::class); } // per-product overrides
    public function validationRules() { return $this->hasMany(ValidationRule::class); }
    public function getRouteKeyName() { return 'slug'; }
    public function fromPrice(float $retailFactor = 1.45, float $vat = 0.20): float
    { return round($this->base_price * $retailFactor * (1 + $vat), 2); }
}
