<?php

namespace App\Http\Controllers;

use App\Http\Resources\FoodMaterialResource;
use App\Models\FoodMaterials;
use Illuminate\Http\Request;
use DB;
use Exception;

class FoodMaterialsController extends Controller
{
    public function index()
    {
        $data = FoodMaterials::all();
        return new FoodMaterialResource(true, "Data Loaded", $data);
    }

    public function store(Request $request)
    {
        $materialData = $request->only(['material_name']);
        DB::beginTransaction();
        try {
            $material = FoodMaterials::create($materialData);
            DB::commit();
            return new FoodMaterialResource(true, "Data Saved", $material);
        } catch (Exception $e) {
            DB::rollback();
            return new FoodMaterialResource(true, $e->getMessage(), $e);
        }
    }
    // public function show(FoodMaterials $foodMaterial)
    public function show($id)
    {
        $foodMaterial = FoodMaterials::find($id);
        return $foodMaterial
            ? new FoodMaterialResource(true, "Data Found", $foodMaterial)
            : new FoodMaterialResource(true, "Data Not Found", $id);
    }
    public function update(Request $request, $id)
    {
        $materialData = $request->only(['material_name']);
        $material = FoodMaterials::find($id)->update($materialData);
        return new FoodMaterialResource(true, $material, $id);
    }

    public function destroy($id)
    {
        try {
            $foodMaterial = FoodMaterials::find($id);
            if (!$foodMaterial) {
                return new FoodMaterialResource(true, "Data Not Found");
            }
            $foodMaterial->delete();
            return new FoodMaterialResource(true, "Data Deleted");
        } catch (Exception $e) {
            return new FoodMaterialResource(true, $e->getMessage(), $e);
        }
    }
}
