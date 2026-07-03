<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DeliveryRule extends Model
{ protected $fillable = ['label','method','amount','is_active'];
  protected $casts = ['amount'=>'decimal:2','is_active'=>'boolean']; }
