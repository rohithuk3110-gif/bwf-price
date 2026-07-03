<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['parent_id','name','slug','blurb','sort_order','is_active'];
    public function parent() { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order'); }
    public function products() { return $this->hasMany(Product::class)->orderBy('sort_order'); }
    public function getRouteKeyName() { return 'slug'; }
}
