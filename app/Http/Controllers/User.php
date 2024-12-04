<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Http\Request;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ModelsUser::with('files')->with('scores')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validated();
        return ModelsUser::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ModelsUser::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validated();
        $user = ModelsUser::find($id);
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = ModelsUser::find($id);
        $user->delete();
        return $user;
    }
}
