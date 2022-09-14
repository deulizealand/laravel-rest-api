<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Foods extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['food_name', 'food_price'];

    public function foodDetails()
    {
        return $this->hasMany(FoodDetails::class, 'food_id');
    }
}
