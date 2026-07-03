<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class VatRule extends Model
{ protected $fillable = ['code','label','rate','is_default'];
  protected $casts = ['rate'=>'decimal:4','is_default'=>'boolean']; }
