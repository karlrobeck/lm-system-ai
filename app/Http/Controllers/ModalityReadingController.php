<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\ModalityReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Raw;

class ModalityReadingController extends Controller
{
    public function getReadingTest(Request $request,$mode,$id) {

        $user = Auth::guard('sanctum')->user();

        $file = Files::query()->where('id','=',$id)->where('owner_id','=',$user->id)->first();
        if ($file == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $modality = ModalityReading::query()->where('test_type','=',$mode)->where('file_id',$id)->get();
        return response()->json($modality);
    }
}
