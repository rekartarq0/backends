<?php

namespace App\Http\Controllers\Api\Quantity;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuantityRequest;
use Illuminate\Http\Request;

class QuantityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $Quantity = auth()->user()->quantity()
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->WhereHas('food', function ($q) use ($search) {
                        $q->whereAny(['name_en', 'name_ar', 'name_ckb'], 'like', '%' . $search . '%');
                    });
                }
            })
            ->with(['food:id,name_en,name_ar,name_ckb', 'user:id,name'])
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString(); // Paginate with the given per_page value

        return $Quantity;
    }
    public function store(QuantityRequest $request)
    {
        $quantity = $request->validated();
        auth()->user()->quantity()->create($quantity);

        return response()->json([
            'message' => 'Quantity created successful',
            'data' => $quantity
        ], 200);
    }
    public function edit($id)
    {
        $food = auth()->user()->quantity()->select('id', 'quantity', 'food_id','expire_date')->with('food:id,name_en')->findOrfail($id);
        return response()->json([
            'data' => $food
        ], 200);
    }

    public function update(QuantityRequest $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validated();
        $quantity = auth()->user()->quantity()->findOrFail($id);
        // Update the quantity record
        $quantity->update($validatedData);
        $updatedQunatity = auth()->user()->quantity()->findOrFail($id);

        return response()->json([
            'message' => 'quantity updated successfully',
            'data' => $updatedQunatity,
        ], 200);
    }
    public function destroy($id)
    {
        $quantity = auth()->user()->quantity()->findOrFail($id);
        $quantity->delete();
        return response()->json([
            'message' => 'quantity deleted successfully',
        ], 200);
    }
    public function quantity_input()
    {
        $categories = auth()->user()->quantity()->select('id', 'food_id')->with('food:id,name_en')->get();
        return response()->json([
            'data' => $categories
        ], 200);
    }
}
