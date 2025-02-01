<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function store(Request $request) {

        // get user response
        $response = $request->json()->all();

        $request_user = $request->user();

        $db_user = User::query()->find($request_user->id);

        $db_user['has_assessment'] = true;
        $db_user['assessment_content'] = $response;

        $db_user->save();
        
        // redirect to the dashboard
        return redirect('/dashboard');
    }
}
