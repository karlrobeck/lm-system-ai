<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\ModalityAuditory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModalityAuditoryController extends Controller
{
    public function getAuditoryTest(Request $request,$mode,$id) {
        $user = Auth::guard('sanctum')->user();

        $file = Files::query()->where('id','=',$id)->where('owner_id','=',$user->id)->first();
        
        if ($file == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $modality = ModalityAuditory::query()->where('test_type','=',$mode)->where('file_id',$id)->get();
        
        return response()->json($modality);
    }
}
