<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuoteItem extends Model
{
    protected $fillable = ['quote_id','product_id','quantity','width_mm','height_mm',
        'configuration','breakdown','unit_price','line_total'];
    protected $casts = ['configuration'=>'array','breakdown'=>'array','unit_price'=>'decimal:2','line_total'=>'decimal:2'];
    public function quote() { return $this->belongsTo(Quote::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
