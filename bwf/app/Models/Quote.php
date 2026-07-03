<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Quote extends Model
{
    protected $fillable = ['reference','customer_id','price_list_code','vat_code','status',
        'items_total','delivery','vat_amount','grand_total','lead_time','valid_until'];
    protected $casts = ['items_total'=>'decimal:2','delivery'=>'decimal:2','vat_amount'=>'decimal:2',
        'grand_total'=>'decimal:2','valid_until'=>'date'];
    public function items() { return $this->hasMany(QuoteItem::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function getRouteKeyName() { return 'reference'; }
}
