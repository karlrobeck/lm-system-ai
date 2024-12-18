<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalityVisualizationRequest;
use App\Http\Requests\UpdateModalityVisualizationRequest;
use App\Models\ModalityVisualization;

class ModalityVisualizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ModalityVisualization::with('context_file')->with('image_file')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModalityVisualizationRequest $request)
    {
        $request->validated();
        return ModalityVisualization::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ModalityVisualization::with('context_file')->with('image_file')->find($id);
    }

    public function showByContextFile(string $id)
    {
        return ModalityVisualization::with('context_file')->with('image_file')->where('context_file_id', $id)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModalityVisualizationRequest $request, ModalityVisualization $modalityVisualization)
    {
        $request->validated();
        $modalityVisualization->update($request->all());
        return $modalityVisualization;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modalityVisualization = ModalityVisualization::find($id);
        $modalityVisualization->delete();
        return $modalityVisualization;
    }
}
