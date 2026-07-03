<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attribute extends Model
{
    protected $fillable = ['attribute_group_id','code','label','input_type','default_value',
        'parent_code','parent_trigger','sort_order','is_required'];
    public function group() { return $this->belongsTo(AttributeGroup::class, 'attribute_group_id'); }
    public function options() { return $this->hasMany(AttributeOption::class)->orderBy('sort_order'); }
}
