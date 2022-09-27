<?php

namespace App\Repositories;

use App\Http\Requests\FoodRequest;
use App\Interfaces\FoodInterface;
use App\Models\Foods;
use App\Models\FoodDetails;
use App\Traits\ResponseAPI;
use Exception;
use DB;

class FoodRepository implements FoodInterface
{
    use ResponseAPI;

    public function getAllFoods($request)
    {
        try {
            $food_name = $request->food_name;
            $foods = Foods::with('foodDetails.foodMaterials')
                ->when($food_name !== null, function ($query) use ($food_name) {
                    $query->where('food_name', 'like', "%$food_name%");
                })
                ->paginate(10);

            $foods->map(function ($data) {
                $data->foodDetails->map(function ($detail) {
                    $detail->food_material_name = $detail->foodMaterials->material_name;
                    return $detail;
                });
                return $data;
            });
            return $this->success("Foods Loaded", $foods);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e);
        }
    }

    public function storeFood($request)
    {
        DB::beginTransaction();
        try {
            $food = Foods::create($request->validated());
            $foodID = $food->id;
            $foodDetails = $request->food_details;
            foreach ($foodDetails as $foodDetail) {
                $foodDetailData = [
                    "food_material_id" => $foodDetail['food_material_id'],
                    "food_id" => $foodID
                ];
                FoodDetails::create($foodDetailData);
            }
            DB::commit();
            return $this->success("Data Food Saved", $food);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e);
        }
    }
    // public function getFoodById($id)
    // {
    // }
    // public function deleteFood($id)
    // {
    // }
}
