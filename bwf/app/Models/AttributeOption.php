<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AttributeOption extends Model
{ protected $fillable = ['attribute_id','code','label','swatch_hex','lead_time_days','sort_order','is_active'];
  public function attribute() { return $this->belongsTo(Attribute::class); } }
