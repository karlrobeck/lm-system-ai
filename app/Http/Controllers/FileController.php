<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\ModalityVisualization;
use App\Models\User;
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

        $user = Auth::guard('sanctum')->user();

        // Instantiate QuizService (which internally calls ChatGPT)
        $quizService = new QuizService();

        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        // Parse file content (if PDF, extract text; if plain text, read directly)
        $content = $this->parseFileContent($path);

        // Generate study notes via ChatGPT
        $studyNotes = $quizService->generateStudyNotes($content);

        $file = Files::create([
            'name'         => $file->getClientOriginalName(),
            'path'         => $path,
            'study_notes'  => $studyNotes, // Generated study notes from ChatGPT
            'is_ready'     => true,
            'type'         => $file->getClientMimeType(),
            'owner_id'     => $user->id,
        ]);

        $quizService->generate_test($content, $user, $file, 'pre');
        $quizService->generate_test($content, $user, $file, 'post');

        if ($user['has_assessment'] === true) {
            $quizService->generateAssessment($user, $file);
            $db_user = User::query()->find($user->id);
            $db_user['has_assessment'] = false;
            $db_user->save();
        }

        // generate images base on the file id
        $visualization = ModalityVisualization::query()->where('file_id', '=', $file->id)->get();

        $index = 0;
        while ($index < count($visualization)) {
            $value = $visualization[$index];
            $retry = 0;
            while ($retry < 3) {
                try {
                    $value->image_url = $quizService->generateImage($value->image_prompt);
                    $value->save();
                    $index++; // Move to the next index if save is successful
                    break; // Exit the retry loop
                } catch (\Exception $e) {
                    Log::error('Error generating image for visualization modality, retrying...');
                    $retry++;
                }
            }
            if ($retry == 3) {
                Log::error('Failed to generate image after 3 attempts, moving to next item.');
                $index++; // Forcefully move to the next index after 3 retries
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
        $mimeType = \Illuminate\Support\Facades\File::mimeType(storage_path('app/public/' . $filePath));
        // If the file is plain text, return its content directly
        if (strpos($mimeType, 'text') !== false) {
            return Storage::disk('public')->get($filePath);
        }

        // If the file is a PDF, extract text using the PDF parser
        if ($mimeType === 'application/pdf') {
            $parser = new Parser();
            $fullPath = storage_path('app/public/' . $filePath);
            $pdf = $parser->parseFile($fullPath);
            error_log($pdf->getText());
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
