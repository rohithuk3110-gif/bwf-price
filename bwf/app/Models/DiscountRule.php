<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DiscountRule extends Model
{ protected $fillable = ['code','label','percent','applies_to','min_margin_floor','is_active'];
  protected $casts = ['percent'=>'decimal:4','min_margin_floor'=>'decimal:4','is_active'=>'boolean']; }
