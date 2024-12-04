<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScoresRequest;
use App\Http\Requests\UpdateScoresRequest;
use App\Models\Scores;

class ScoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Scores::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScoresRequest $request)
    {
        $request->validated();
        return Scores::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id)
    {
        return Scores::where('user_id', $user_id)->get();
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScoresRequest $request, Scores $scores)
    {
        $request->validated();
        $scores->update($request->all());
        return $scores;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scores $scores)
    {
        $scores->delete();
        return $scores;
    }
}
