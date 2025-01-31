<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function uploadFile(Request $request) {

        $request->validate([
            'file' => 'required',
        ]);
        
        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        // send GPT request
        /*$
            gpt_service = new GPTService();
            
            $reading_pre_test_response = $gpt_service->create_reading_modality($path,"pre");
            $reading_post_test_response = $gpt_service->create_reading_modality($path,"post");

            $writing_pre_test_response = $gpt_service->create_writing_modality($path,"pre");
            $writing_post_test_response = $gpt_service->create_writing_modality($path,"post");

            $auditory_pre_test_response = $gpt_service->create_auditory_modality($path,"pre");
            $auditory_post_test_response = $gpt_service->create_auditory_modality($path,"post");

            $kinesthetic_pre_test_response = $gpt_service->create_kinesthetic_modality($path,"pre");
            $kinesthetic_post_test_response = $gpt_service->create_kinesthetic_modality($path,"post");

            $visualization_pre_test_response = $gpt_service->create_visualization_modality($path,"pre");
            $visualization_post_test_response = $gpt_service->create_visualization_modality($path,"post");
        */
        // save the pre and post test to their respective tables

        // save the file metadata in the database

        $metadata = Files::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'is_ready' => true,
            'type' => $file->getClientMimeType(),
            'owner_id' => $request->user()->id,
        ]);
        

        return $metadata;
    }

    public function getFile($id) {
        $user = Auth::guard('sanctum')->user();
        $file = Files::query()->where('id','=',$id)->where('owner_id','=',$user->id)->first();
        if ($file->ready) {
            return Storage::get($file->path);
        } else {
            // this is where you call gpt to ask if the file is ready
            return response()->json(['error' => 'File not ready'], 400);
        }
    }

    public function getAllFileMetadata($request) {
        $user = Auth::guard('sanctum')->user();
        return Files::query()->where('owner_id','=',$user->id)->get();
    }

    public function getFileMetadata(Request $request) {
        // convert $id to an integer
        $user = Auth::guard('sanctum')->user();
        $id = (int)$request->route('id');
        $file = Files::query()->where('id', '=', $id)->where('owner_id', '=', $user->id)->with('user')->first();
        
        if($file == null) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return $file;
    }
}
