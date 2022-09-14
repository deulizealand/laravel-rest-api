<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Http\Resources\FoodsResource;
use App\Models\FoodDetails;
use App\Models\Foods;
use Illuminate\Http\Request;
use DB;
use Exception;

class FoodsController extends Controller
{
    public function index(Request $request)
    {
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
        return new FoodsResource(true, "Data Loaded", $foods);
    }

    public function store(FoodRequest $request)
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
            return new FoodsResource(true, "Data Saved", $request->validated());
        } catch (Exception $e) {
            DB::rollback();
            return new FoodsResource(true, $e->getMessage(), $e);
        }
    }

    public function update(Request $request, $id)
    {
        $foods = Foods::find($id)->update($request->all());
        $foodDetails = $request->food_details;
        foreach ($foodDetails as $foodDetail) {
            $foodDetailData = [
                "food_material_id" => $foodDetail['food_material_id'],
                "food_id" => $id
            ];
            FoodDetails::where('food_id', $id)->updateOrCreate($foodDetailData);
        }
        return new FoodsResource(true, "Data Found", $request->all());
    }

    public function destroy($id)
    {
        $food = Foods::find($id);
        $food->delete();
        return new FoodsResource(true, "Data Deleted");
    }
}
