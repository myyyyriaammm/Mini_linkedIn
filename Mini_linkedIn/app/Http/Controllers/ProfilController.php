<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated =$request->validate([
            'titre' => 'required|string|max:20',
            'bio' => 'nullable|string|max:150',
            'localisation' => 'required|string|max:50',
            'disponible' => 'required|boolean',
        ]);

        if(auth()->user()->profil){
            return response()->json(['message' => 'Profil already exists'], 422);
        }

        $validated['user_id'] = auth()->id();
        $profil = Profil::create($validated);

        return response()->json([
            'data' => $profil,
            'message' => 'Profil created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $profil = auth()->user()->profil;

        if(!$profil){
            return response()->json([
                'message' => 'Profil not found',
            ],404);
        }


        return response()->json([
            'data' => $profil,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $profil = auth()->user()->profil;

        if(!$profil){
            return response()->json([
                'message' => 'Profil not found',
            ], 404);
        }

        $validated =$request->validate([
            'titre' => 'sometimes|string|max:20',
            'bio' => 'nullable|string|max:150',
            'localisation' => 'sometimes|string|max:50',
            'disponible' => 'sometimes|boolean',
        ]);

        $profil->update($validated);

        return response()->json([
            'data' => $profil,
            'message' => 'updated successfully',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
