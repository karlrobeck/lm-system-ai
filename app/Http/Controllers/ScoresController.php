<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\ModalityAuditory;
use App\Models\ModalityKinesthetic;
use App\Models\ModalityReading;
use App\Models\ModalityVisualization;
use App\Models\ModalityWriting;
use App\Models\Scores;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoresController extends Controller
{
    public function index() {
        // show all scores
        $user = Auth::guard('sanctum')->user();
        return Scores::query()->with('user')->with('file')->where('user_id','=',$user->id)->get();
    }

    public function show($id) {
        $user = Auth::guard('sanctum')->user();
        return Scores::query()->with('user')->with('file')->where('user_id','=',$user->id)->where('file_id','=',$id)->get();
    }

    public function store($request,$mode,$modality,$id) {

        $quiz_service = new QuizService();

        $user = Auth::guard('sanctum')->user();

        $file = Files::query()->where('id', $id)->where('owner_id', $user->id)->first();

        if (!$file) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // lets get all of the json data from the request
        $payload = $request->json()->all();

        // send the data to the GPT. note: this is a stripped down json array that is stringified. you can directly send the json array to the GPT
        $answers_string = json_encode($payload);

        $system_prompt = "You are a teacher. You are grading a student's test. Read the student's response and give a score from 0 to n based on this database of answers. \n\n";

        $gpt_response = null;

        $modalityModelMap = [
            'reading' => ModalityReading::class,
            'writing' => ModalityWriting::class,
            'auditory' => ModalityAuditory::class,
            'kinesthetic' => ModalityKinesthetic::class,
            'visualization' => ModalityVisualization::class,
        ];

        if (!array_key_exists($modality, $modalityModelMap)) {
            return response()->json(['error' => 'Invalid modality'], 400);
        }

        $corrected_answer = json_encode($modalityModelMap[$modality]::query()
            ->where('test_type', $mode)
            ->where('file_id', $id)
            ->get());

        $user_prompt = "---database answers---\n\n"
            . $corrected_answer
            . "\n\n---student answers---\n\n"
            . $answers_string;

        $gpt_response = $quiz_service->getGPTResponse($system_prompt, $user_prompt);

        Scores::factory()->create([
            'user_id' => $user->id,
            'file_id' => $file->id,
            'correct' => $gpt_response['correct'], // change this to the correct number of answers
            'total' => $gpt_response['total'], // change this to the total number of answers
            'test_type' => $mode,
            'modality' => $modality,
            'is_passed' => $gpt_response['is_passed'], // change this to false if the user failed 60% or less
        ]);

        return redirect('/dashboard');
    }
}
