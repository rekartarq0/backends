<?php

namespace App\Http\Controllers\Api\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Resources\FoodResource;
use App\trait\UploadFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    use UploadFile;

    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $Foods = auth()->user()->food()->where(function ($query) use ($search) {
            $query->whereAny(['name_en', 'name_ckb', 'name_ar'], 'like', '%' . $search . '%');
        })
            ->with(['user:id,name', 'category:id,name_en'])
            ->OrderByDesc('id')
            ->paginate($perPage)
            ->withQueryString(); // Paginate with the given per_page value

        return FoodResource::collection($Foods);
    }
    public function store(StoreFoodRequest $request)
    {
        $Food = $request->validated();

        if ($request->hasFile('image')) {
            $Food['image'] = $this->Upload_image($request, 'image', 'food_img');
        }
        auth()->user()->food()->create($Food);

        return response()->json([
            'message' => 'Food created successful',
            'data' => $Food
        ], 200);
    }
    public function edit($id)
    {
        $food = auth()->user()->food()->select('id', 'name_en', 'name_ckb', 'name_ar', 'image', 'category_id', 'is_available')->findOrfail($id);
        return response()->json([
            'data' => $food
        ], 200);
    }
    public function update(StoreFoodRequest $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validated();

        // Check if a new image file is uploaded
        if ($request->hasFile('image')) {
            // Upload new image
            $validatedData['image'] = $this->Upload_image($request, 'image', 'food_img');

            // Delete old image if it exists
            $food = auth()->user()->food()->find($id);
            if ($food && $food->image && file_exists('food_img/' . $food->image)) {
                unlink('food_img/' . $food->image);
            }
        } else {
            // Remove image from validated data if no new image uploaded
            unset($validatedData['image']);
        }

        // Update the food record
        auth()->user()->food()->where('id', $id)->update($validatedData);

        // Retrieve the updated food data
        $foodUpdated = auth()->user()->food()->find($id);

        return response()->json([
            'message' => 'food updated successfully',
            'data' => $foodUpdated,
        ], 200);
    }
    public function destroy($id)
    {
        try {
            $food = auth()->user()->food()->findOrFail($id);

            // Delete food's image file if it exists
            if ($food->image && file_exists('food_img/' . $food->image)) {
                unlink('food_img/' . $food->image);
            }

            // Delete the food
            $food->delete();

            return response()->json([
                'message' => 'food deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'food not found'
            ], 422);
        }
    }
    public function category_input(){
        $categories = auth()->user()->category()->select('id','name_en')->get();
        return response()->json([
            'data' => $categories
        ], 200);
    }
}
