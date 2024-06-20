<?php

namespace App\Http\Controllers\Api\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\TableRequest;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $tables = auth()->user()->table() // Adjust 'tables' to your relationship method
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('table_number', 'like', '%' . $search . '%');
                }
            })
            ->with(['user:id,name']) // Adjust 'user' to your relationship method
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString(); // Paginate with the given per_page value

        return $tables;
    }
    public function store(TableRequest $request)
    {
        $table = $request->validated();
        $data = auth()->user()->table()->create($table);

        return response()->json([
            'message' => 'Table created successful',
            'data'  => $data
        ], 201);
    }
    public function edit($id)
    {
        $table = auth()->user()->table()->select('id', 'table_number', 'seating_capacity', 'status')->findOrfail($id);
        return response()->json([
            'data' => $table
        ], 200);
    }
    public function update(TableRequest $request, $id)
    {
        $validatedData = $request->validated();
        $table = auth()->user()->table()->findOrFail($id);
        $table->update($validatedData);

        return response()->json([
            'message' => 'Table updated successful',
            'data' => $table
        ], 200);
    }
    public function destroy($id){
        $table = auth()->user()->table()->findOrFail($id);
        $table->delete();
        return response()->json([
           'message' => 'Table deleted successful',
            'data' => $table
        ], 200);
    }
}
