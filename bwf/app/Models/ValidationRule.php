<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ValidationRule extends Model
{
    protected $fillable = ['product_id','scope','rule_type','attribute_code','value_number',
        'force_attribute','force_value','severity','message','is_active'];
    protected $casts = ['value_number'=>'decimal:2','is_active'=>'boolean'];
}
