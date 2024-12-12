<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index($eventId)
    {
        $equipment = Equipment::where('event_id', $eventId)->get();
        return response()->json($equipment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'item_name' => 'required|string|max:255',
            'total_items' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::create($request->all());
        return response()->json($equipment, 201);
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->update($request->all());
        return response()->json($equipment);
    }

    public function destroy($id)
    {
        Equipment::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function getAllEquipment()
    {
        $equipment = Equipment::all();
        return response()->json($equipment, 200);
    }

}
