<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PriceList extends Model
{ protected $fillable = ['code','label','method','factor','is_default'];
  protected $casts = ['factor'=>'decimal:4','is_default'=>'boolean']; }
