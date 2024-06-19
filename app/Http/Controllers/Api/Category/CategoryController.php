<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\trait\UploadFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use UploadFile;

    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $categories = auth()->user()->category()->where(function ($query) use ($search) {
            $query->whereAny(['name_en', 'name_ckb', 'name_ar'], 'like', '%' . $search . '%');
        })
            ->with(['user:id,name'])
            ->OrderByDesc('id')
            ->paginate($perPage)
            ->withQueryString(); // Paginate with the given per_page value

        return CategoryResource::collection($categories);
    }
    public function store(CategoryRequest $request)
    {
        $category = $request->validated();

        if ($request->hasFile('image')) {
            $category['image'] = $this->Upload_image($request, 'image', 'category_img');
        }
        auth()->user()->category()->create($category);

        return response()->json([
            'message' => 'category created successful',
            'data' => $category
        ], 200);
    }
    public function edit($id)
    {
        $category = auth()->user()->category()->select('id', 'name_en', 'name_ckb', 'name_ar', 'image')->findOrfail($id);

        return response()->json([
            'data' => $category
        ], 200);
    }
    public function update(CategoryRequest $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validated();

        // Check if a new image file is uploaded
        if ($request->hasFile('image')) {
            // Upload new image
            $validatedData['image'] = $this->Upload_image($request, 'image', 'category_img');

            // Delete old image if it exists
            $category = auth()->user()->category()->find($id);
            if ($category && $category->image && file_exists('category_img/' . $category->image)) {
                unlink('category_img/' . $category->image);
            }
        } else {
            // Remove image from validated data if no new image uploaded
            unset($validatedData['image']);
        }

        // Update the category record
        auth()->user()->category()->where('id', $id)->update($validatedData);

        // Retrieve the updated category data
        $updatedCategory = auth()->user()->category()->find($id);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $updatedCategory,
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $category = auth()->user()->category()->findOrFail($id);

            // Delete category's image file if it exists
            if ($category->image && file_exists('category_img/' . $category->image)) {
                unlink('category_img/' . $category->image);
            }

            // Delete the category
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Category not found'
            ], 422);
        }
    }
}
