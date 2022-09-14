<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDetails extends Model
{
    use HasFactory;

    protected $fillable = ['food_id', 'food_material_id'];

    public function foods()
    {
        return $this->belongsTo(Foods::class);
    }

    public function foodMaterials()
    {
        return $this->belongsTo(FoodMaterials::class, 'food_material_id');
    }
}
