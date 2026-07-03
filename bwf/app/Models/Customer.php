<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model
{ protected $fillable = ['name','email','phone','postcode','price_list_id'];
  public function quotes() { return $this->hasMany(Quote::class); } }
