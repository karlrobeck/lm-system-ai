<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $payload = $request->json()->all();
        $user = ModelsUser::create($payload);
        return ModelsUser::with('files')->with('scores')->find($user['id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();
        return ModelsUser::with('files')->with('scores')->where('id', $user->id);
    }

    public function me(Request $request)
    {
        return ModelsUser::with('files')->with('scores')->find($request->user()->id);
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
