<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilesRequest;
use App\Http\Requests\UpdateFilesRequest;
use App\Models\Files;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = Files::with('user')->get();
        return $files;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilesRequest $request)
    {
        $request->validated();
        return Files::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $file = Files::with('user')->where('id', $id)->first();
        if (!$file) {
            return response()->json(['error' => 'File not found or you do not have permission to view it'], 404);
        }
        return $file;
    }

    public function showFile(string $id)
    {
        $file = Files::where('id', $id)->first();
        if (!$file) {
            return response()->json(['error' => 'File not found or you do not have permission to view it'], 404);
        }
        return response()->download(storage_path('app/' . $file->path));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilesRequest $request, Files $files)
    {
        $request->validated();
        $files->update($request->all());
        return $files;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $files = Files::find($id);
        $files->delete();
        return $files;
    }
}
