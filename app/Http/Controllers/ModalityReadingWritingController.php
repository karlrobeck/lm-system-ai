<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalityReadingWritingRequest;
use App\Http\Requests\UpdateModalityReadingWritingRequest;
use App\Models\ModalityReadingWriting;

class ModalityReadingWritingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ModalityReadingWriting::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModalityReadingWritingRequest $request)
    {
        $request->validated();
        return ModalityReadingWriting::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ModalityReadingWriting::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModalityReadingWritingRequest $request, ModalityReadingWriting $modalityReadingWriting)
    {
        $request->validated();
        $modalityReadingWriting->update($request->all());
        return $modalityReadingWriting;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modalityReadingWriting = ModalityReadingWriting::find($id);
        $modalityReadingWriting->delete();
        return $modalityReadingWriting;
    }
}
