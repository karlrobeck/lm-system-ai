<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\ModalityWriting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModalityWritingController extends Controller
{
    public function getWritingTest(Request $request,$mode,$id) {

        $user = Auth::guard('sanctum')->user();

        $file = Files::query()->where('id','=',$id)->where('owner_id','=',$user->id)->first();
        
        if ($file == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $modality = ModalityWriting::query()->where('test_type','=',$mode)->where('file_id',$id)->get();
        
        return response()->json($modality);
    }
}
