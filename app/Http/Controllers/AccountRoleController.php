<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountRoleController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            // $accountRole = AccountRole::create($request->all());

            // return response($accountRole, 201);
            $validatedData = $request->validate([
                // 'user_id' => 'required|exists:users,id',
                'role_id' => 'exists:roles,id',
                'service_provider_name' => 'string|max:255',
                'description' => 'string',
            ]);
            $validatedData['user_id'] = $user->id;
            $accountRole = AccountRole::create($validatedData);
            return response()->json($accountRole, 201);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
