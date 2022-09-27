<?php

namespace App\Interfaces;

use App\Http\Requests\FoodRequest;

interface FoodInterface
{
    public function getAllFoods($request);
    public function storeFood($request);
    // public function getFoodById($id);
    // public function deleteFood($id);
}
