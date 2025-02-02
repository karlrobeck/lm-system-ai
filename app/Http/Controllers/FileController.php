<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\VisualizationPreTest;
use App\Models\VisualizationPostTest;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser; // Make sure you've installed smalot/pdfparser via Composer

class FileController extends Controller
{
    public function uploadFile(Request $request)
    {
        // Validate that a file is provided and is a valid file
        $request->validate([
            'file' => 'required|file',
        ]);

        // Instantiate QuizService (which internally calls ChatGPT)
        $quizService = new QuizService();

        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        // Parse file content (if PDF, extract text; if plain text, read directly)
        $content = $this->parseFileContent($path);

        // Create a custom prompt to generate study notes and test questions
        $prompt = "Using ChatGPT, generate a concise summary and key study notes, then create test questions based on the following text:\n\n" 
            . $content;
        
        // Generate study notes via ChatGPT
        $studyNotes = $quizService->generateStudyNotes($prompt);
        
        // Generate test responses for each modality and test type using the same prompt
        $modalities = ['reading', 'writing', 'auditory', 'kinesthetic', 'visualization'];
        $testTypes = ['pre', 'post'];
        $allResponses = [];

        foreach ($modalities as $modality) {
            foreach ($testTypes as $test_type) {
                $method = "create_{$modality}_modality";
                if (method_exists($quizService, $method)) {
                    $response = $quizService->$method($prompt, $test_type);
                    if ($response === null) {
                        return response()->json([
                            'error' => "Failed to generate {$modality} {$test_type}-test."
                        ], 500);
                    }
                    $allResponses["{$modality}_{$test_type}_test"] = $response;
                } else {
                    Log::warning("Method {$method} does not exist in QuizService.");
                }
            }
        }

        // Save the file metadata along with the generated study notes to the database
        $metadata = Files::create([
            'name'         => $file->getClientOriginalName(),
            'path'         => $path,
            'study_notes'  => $studyNotes, // Generated study notes from ChatGPT
            'is_ready'     => true,
            'type'         => $file->getClientMimeType(),
            'owner_id'     => $request->user()->id,
        ]);

        // Map response keys to model classes (example mapping for visualization tests)
        $responseToModelMap = [
            'visualization_pre_test'  => VisualizationPreTest::class,
            'visualization_post_test' => VisualizationPostTest::class,
        ];

        // Loop through all responses and save them accordingly
        foreach ($allResponses as $responseKey => $responseData) {
            if (isset($responseToModelMap[$responseKey])) {
                $model = $responseToModelMap[$responseKey];
                $data = [
                    'file_id'        => $metadata->id,
                    'question_index' => $responseData['question_index'] ?? null,
                    'question'       => $responseData['question'] ?? null,
                    'test_type'      => $responseData['test_type'] ?? null,
                ];

                // Determine modality from the response key
                $modality = explode('_', $responseKey)[0];

                if (in_array($modality, ['reading', 'auditory'])) {
                    $data['choices']        = isset($responseData['choices']) ? json_encode($responseData['choices']) : null;
                    $data['correct_answer'] = $responseData['correct_answer'] ?? null;
                }

                if (in_array($modality, ['writing', 'kinesthetic'])) {
                    $data['answer'] = $responseData['context_answer'] ?? null;
                }

                if ($modality === 'visualization') {
                    $data['image_prompt'] = $responseData['image_prompt'] ?? null;
                    $data['image_url']    = $responseData['image_url'] ?? null;
                }

                $model::create($data);
            } else {
                Log::warning("No model mapped for response key: {$responseKey}");
            }
        }

        return response()->json(['message' => 'File uploaded and tests generated successfully.'], 201);
    }

    /**
     * Parse the uploaded file's content into plain text.
     *
     * This method checks the MIME type. For PDFs, it uses the PDF parser to extract text.
     *
     * @param string $filePath
     * @return string
     */
    private function parseFileContent($filePath)
    {
        $mimeType = Storage::disk('public')->mimeType($filePath);

        // If the file is plain text, return its content directly
        if (strpos($mimeType, 'text') !== false) {
            return Storage::disk('public')->get($filePath);
        }

        // If the file is a PDF, extract text using the PDF parser
        if ($mimeType === 'application/pdf') {
            $parser = new Parser();
            $fullPath = storage_path('app/public/' . $filePath);
            $pdf = $parser->parseFile($fullPath);
            return $pdf->getText();
        }

        // For other file types, attempt to return the content as a string
        return (string) Storage::disk('public')->get($filePath);
    }

    public function getFile($id)
    {
        $user = Auth::guard('sanctum')->user();
        $file = Files::query()
            ->where('id', '=', $id)
            ->where('owner_id', '=', $user->id)
            ->first();

        if ($file && $file->is_ready) {
            return Storage::disk('public')->get($file->path);
        } else {
            return response()->json(['error' => 'File not ready'], 400);
        }
    }

    public function getAllFileMetadata(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        return Files::query()->where('owner_id', '=', $user->id)->get();
    }

    public function getFileMetadata(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $id = (int)$request->route('id');
        $file = Files::query()
            ->where('id', '=', $id)
            ->where('owner_id', '=', $user->id)
            ->with('user')
            ->first();

        if ($file === null) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return $file;
    }
}
