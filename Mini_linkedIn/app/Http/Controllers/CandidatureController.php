<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Candidature;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profil =auth()->user()->profil;

        if(!$profil){
            return response()->json([
                'message' => 'Profil not found',
            ]);
        }

        $candidatures = $profil->candidatures;

        return response()->json([
            'data' => $candidatures,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Offre $offre)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:200',
        ]);

        $validated['offre_id'] = $offre()->id();
        $validated['profil_id'] = $profil->id;
        $validated['statut'] = 'en_attente';

        $candidature = Candidature::create($validated);

        return response()->json([
            'data' => $candidature,
            'message' => 'candidature soumise avec succès',
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function candidaturesOffres(Request $request, Offre $offre)
    {
        if($offre->user_id !== auth()->id()){
            return response()->json([
                'message' => 'Forbidden',
            ], 403);
        }

        $candidatures = $offre->candidatures;

        return response()->json([
            'data' => $candidatures
        ]);

    }

    public function updateStatut(Request $request, Candidature $candidature)
    {
        if($candidature->offre->user_id !== auth()->id()){
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        $validated = $request->validate([
            'statut' => 'required|in:acceptee,refusee',
        ]);

        $candidature->update($validated);

        return response()->json([
            'data' => $candidature,
            'message' => 'Statut updated successfully',
        ]);
    }
}
