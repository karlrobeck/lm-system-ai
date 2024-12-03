<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalityAuditoryRequest;
use App\Http\Requests\UpdateModalityAuditoryRequest;
use App\Models\ModalityAuditory;

class ModalityAuditoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ModalityAuditory::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModalityAuditoryRequest $request)
    {
        $request->validated();
        return ModalityAuditory::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ModalityAuditory::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModalityAuditoryRequest $request, ModalityAuditory $modalityAuditory)
    {
        $request->validated();
        $modalityAuditory->update($request->all());
        return $modalityAuditory;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModalityAuditory $modalityAuditory)
    {
        $modalityAuditory->delete();
        return $modalityAuditory;
    }
}
