<?php

namespace App\Http\Controllers\Api\Table;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationsRequest;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $reservations = auth()->user()->reservation() // Adjust 'reservations' to your relationship method
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->whereAny(['name', 'phone_number'], 'like', '%' . $search . '%');
                }
            })
            ->with(['user:id,name', 'table:id,table_number']) // Adjust 'user' and 'table' to your relationship method
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString(); // Paginate with the given per_page value

        return $reservations;
    }
    public function store(ReservationsRequest $request)
    {
        $reservation = $request->validated();
        $table_id = $reservation['table_id'];
        // create reservation and change status of this table reserved for people   
        auth()->user()->table()->findOrFail($table_id)->update(['status' => '3']);
        auth()->user()->reservation()->create($reservation);

        return response()->json([
            'message' => 'Reservation created successful',
        ], 200);
    }
    public function edit($id)
    {
        $reservation = auth()->user()->reservation()->select('id', 'name', 'phone_number', 'table_id', 'reservation_time')->with('table:id,table_number')->findOrfail($id);
        return response()->json([
            'data' => $reservation
        ], 200);
    }
    public function update(ReservationsRequest $request, $id)
    {
        // Validate the incoming request
        $validatedData = $request->validated();
        $reservation = auth()->user()->reservation()->findOrFail($id);
        // Update the reservation record
        $reservation->update($validatedData);
        return response()->json([
            'message' => 'Reservation updated successful',
            'data' => $reservation
        ], 200);
    }
    public function destroy($id){
        // delete reservation and change status of this table reserved for people   
        $reservation = auth()->user()->reservation()->findOrFail($id);
        $table_id = $reservation['table_id'];
        auth()->user()->table()->findOrFail($table_id)->update(['status' => '1']);
        $reservation->delete();
        return response()->json([
           'message' => 'Reservation deleted successful',
        ], 200);
    }

    public function reservation_input()
    {
        $reservation = auth()->user()->table()->select('id', 'table_number')->get();
        return response()->json([
            'data' => $reservation
        ], 200);
    }

}
