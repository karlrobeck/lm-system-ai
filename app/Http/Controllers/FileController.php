<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\QuizService; // Import the QuizService

class FileController extends Controller
{
    private $quizService;

    // Inject the QuizService via the controller's constructor
    public function __construct(QuizService $quizService){
        $this->quizService = $quizService;
    }
    public function uploadFile(Request $request) {

        $request->validate([
            'file' => 'required',
        ]);
        
        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        // Read the content of the file
        $content = Storage::disk('public')->get($path);
        
        // Generate pre-test and post-test for each modality
        $reading_pre_test_response = $this->quizService->create_reading_modality($content, 'pre');
        $reading_post_test_response = $this->quizService->create_reading_modality($content, 'post');

        $writing_pre_test_response = $this->quizService->create_writing_modality($content, 'pre');
        $writing_post_test_response = $this->quizService->create_writing_modality($content, 'post');

        $auditory_pre_test_response = $this->quizService->create_auditory_modality($content, 'pre');
        $auditory_post_test_response = $this->quizService->create_auditory_modality($content, 'post');

        $kinesthetic_pre_test_response = $this->quizService->create_kinesthetic_modality($content, 'pre');
        $kinesthetic_post_test_response = $this->quizService->create_kinesthetic_modality($content, 'post');

        // Visualization modality
        $visualization_pre_test_response = $this->quizService->create_visualization_modality($content, 'pre');
        $visualization_post_test_response = $this->quizService->create_visualization_modality($content, 'post');

         // Check if the responses are not null
         if ($visualization_pre_test_response === null || $visualization_post_test_response === null) {
             return response()->json(['error' => 'Failed to generate visualization test responses.'], 500);
         }
        // Save the file metadata in the database
        $metadata = Files::create([
            'name'     => $file->getClientOriginalName(),
            'path'     => $path,
            'is_ready' => true,
            'type'     => $file->getClientMimeType(),
            'owner_id' => $request->user()->id,
        ]);

        // Save the pre-test and post-test responses
        // Reading Modality
        ReadingPreTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $reading_pre_test_response['question_index'],
            'question'       => $reading_pre_test_response['question'],
            'choices'        => json_encode($reading_pre_test_response['choices']),
            'answer'         => $reading_pre_test_response['correct_answer'],
            'test_type'      => $reading_pre_test_response['test_type'],
        ]);

        ReadingPostTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $reading_post_test_response['question_index'],
            'question'       => $reading_post_test_response['question'],
            'choices'        => json_encode($reading_post_test_response['choices']),
            'answer'         => $reading_post_test_response['correct_answer'],
            'test_type'      => $reading_post_test_response['test_type'],
        ]);

        // Writing Modality
        WritingPreTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $writing_pre_test_response['question_index'],
            'question'       => $writing_pre_test_response['question'],
            'answer'         => $writing_pre_test_response['correct_answer'],
            'test_type'      => $writing_pre_test_response['test_type'],
        ]);

        WritingPostTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $writing_post_test_response['question_index'],
            'question'       => $writing_post_test_response['question'],
            'answer'         => $writing_post_test_response['correct_answer'],
            'test_type'      => $writing_post_test_response['test_type'],
        ]);

        // Auditory Modality
        AuditoryPreTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $auditory_pre_test_response['question_index'],
            'question'       => $auditory_pre_test_response['question'],
            'choices'        => json_encode($auditory_pre_test_response['choices']),
            'answer'         => $auditory_pre_test_response['correct_answer'],
            'test_type'      => $auditory_pre_test_response['test_type'],
        ]);

        AuditoryPostTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $auditory_post_test_response['question_index'],
            'question'       => $auditory_post_test_response['question'],
            'choices'        => json_encode($auditory_post_test_response['choices']),
            'answer'         => $auditory_post_test_response['correct_answer'],
            'test_type'      => $auditory_post_test_response['test_type'],
        ]);

        // Kinesthetic Modality
        KinestheticPreTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $kinesthetic_pre_test_response['question_index'],
            'question'       => $kinesthetic_pre_test_response['question'],
            'answer'         => $kinesthetic_pre_test_response['correct_answer'],
            'test_type'      => $kinesthetic_pre_test_response['test_type'],
        ]);

        KinestheticPostTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $kinesthetic_post_test_response['question_index'],
            'question'       => $kinesthetic_post_test_response['question'],
            'answer'         => $kinesthetic_post_test_response['correct_answer'],
            'test_type'      => $kinesthetic_post_test_response['test_type'],
        ]);

        // Visualization Modality
        VisualizationPreTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $visualization_pre_test_response['question_index'],
            'question'       => $visualization_pre_test_response['question'],
            'choices'        => json_encode($visualization_pre_test_response['choices']),
            'answer'         => $visualization_pre_test_response['correct_answer'],
            'image_prompt'   => $visualization_pre_test_response['image_prompt'],
            'image_url'      => $visualization_pre_test_response['image_url'],
            'test_type'      => $visualization_pre_test_response['test_type'],
        ]);

        VisualizationPostTest::create([
            'file_id'        => $metadata->id,
            'question_index' => $visualization_post_test_response['question_index'],
            'question'       => $visualization_post_test_response['question'],
            'choices'        => json_encode($visualization_post_test_response['choices']),
            'answer'         => $visualization_post_test_response['correct_answer'],
            'image_prompt'   => $visualization_post_test_response['image_prompt'],
            'image_url'      => $visualization_post_test_response['image_url'],
            'test_type'      => $visualization_post_test_response['test_type'],
        ]);

        return response()->json(['message' => 'File uploaded and tests generated successfully.']);
        
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
