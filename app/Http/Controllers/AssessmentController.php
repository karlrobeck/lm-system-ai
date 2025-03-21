<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\User;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function store(Request $request) {
        // get user response
        $request_user = Auth::guard('sanctum')->user();

        $quizService = new QuizService();

        $response = $request->json()->all();

        $db_user = User::query()->find($request_user->id);

        $db_user['has_assessment'] = true;
        $db_user['assessment_content'] = json_encode($response);
        $db_user->save();
        
        $quizService->generateAssessment($db_user);
        
        // redirect to the dashboard
        return redirect('/dashboard');
    }

    public function ranking() {
        // get the current user
        $user = Auth::guard('sanctum')->user();
        
        // get the user from the database
        $db_user = User::query()->find($user->id);

        // get the assessment by the user_id
        $assessment = Assessment::query()->where('user_id', $db_user->id)->get();

        // return the assessment
        return $assessment;
    }
}
