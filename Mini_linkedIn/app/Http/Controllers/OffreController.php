<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offre = Offre::where('actif', true)
                ->when(request('localisation'), fn($q) => $q->where('localisation', request('localisation')))
                ->when(request('type'), fn($q) => $q->where('type',request('type')))
                ->orderby('created_at', 'disc')
                ->paginate(10);

        return response()->json([
            'data' => $offre,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
            'localisation' => 'required|string',
            'type' => 'required|in:CDI,CDD,stage',
        ]);
        
        $validated['user_id'] = auth()->id();
        $validated['actif'] = true;

        $offre = Offre::create($validated);

        return response()->json([
            'data' => $offre,
            'message' => 'Offre created successfully',
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        if(!$offre)
        {
            return response()->json([
                'message' => 'offre not found',
            ],404);
        }

        return response()->json([
            'data' => $offre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offre $offre)
    {
        if(!$offre->user_id !== auth()->id())
        {
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|string',
            'description' => 'sometimes|string',
            'localisation' => 'sometimes|string',
            'type' => 'sometimes|in:CDI,CDD,stage',
        ]);

        $offre->update($validated);

        return response()->json([
            'data' => $offre,
            'message' => 'Offre updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
        if($offre->user_id !== auth()->id())
        {
            return response()->json([
                'message' => 'forbidden'
            ], 403);
        }

        $offre->delete();

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }
}
