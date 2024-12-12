<?php

 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::all();
        return response()->json($inventories);
    }

    public function store(Request $request)
    {
        $inventory = new Inventory();
        $inventory->name = $request->input('name');
        $inventory->category = $request->input('category');
        $inventory->inventory_status = $request->input('inventory_status');
        $inventory->quantity = $request->input('quantity');
        $inventory->quantity_sorted = $request->input('quantity_sorted');
        $inventory->equipment_status = $request->input('equipment_status');
        $inventory->save();
        return response()->json($inventory, 201);
    }

    public function show($id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }
        return response()->json($inventory);
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }
        $inventory->name = $request->input('name');
        $inventory->category = $request->input('category');
        $inventory->inventory_status = $request->input('inventory_status');
        $inventory->quantity = $request->input('quantity');
        $inventory->quantity_sorted = $request->input('quantity_sorted');
        $inventory->equipment_status = $request->input('equipment_status');
        $inventory->save();
        return response()->json($inventory);
    }

    public function destroy($id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }
        $inventory->delete();
        return response()->json(['message' => 'Inventory deleted']);
    }
}