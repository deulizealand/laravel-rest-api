<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodRequest;
use App\Http\Resources\FoodsResource;
use App\Interfaces\FoodInterface;
use App\Models\FoodDetails;
use App\Models\Foods;
use Illuminate\Http\Request;
use DB;
use Exception;

class FoodsController extends Controller
{
    protected $foodInterface;

    public function __construct(FoodInterface $foodInterface)
    {
        $this->foodInterface = $foodInterface;
    }

    public function index(Request $request)
    {
        return $this->foodInterface->getAllFoods($request);
    }

    public function store(FoodRequest $request)
    {
        return $this->foodInterface->storeFood($request);
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
