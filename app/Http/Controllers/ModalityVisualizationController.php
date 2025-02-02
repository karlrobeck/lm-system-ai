<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\VisualizationPreTest;
use App\Models\VisualizationPostTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModalityVisualizationController extends Controller
{
    /**
     * Retrieve visualization test based on mode and file ID.
     *
     * @param Request $request
     * @param string $mode ('pre' or 'post')
     * @param int $id (file ID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVisualizationTest(Request $request, $mode, $id)
    {
        // Authenticate user
        $user = Auth::guard('sanctum')->user();

        // Verify file ownership
        $file = Files::where('id', $id)->where('owner_id', $user->id)->first();
        
        if (!$file) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Fetch visualization test based on mode
        if ($mode === 'pre') {
            $modality = VisualizationPreTest::where('test_type', $mode)
                ->where('file_id', $id)
                ->get();
        } elseif ($mode === 'post') {
            $modality = VisualizationPostTest::where('test_type', $mode)
                ->where('file_id', $id)
                ->get();
        } else {
            return response()->json(['error' => 'Invalid test type'], 400);
        }
        
        return response()->json($modality);
    }
}