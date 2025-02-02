<?php

namespace Database\Seeders;

use App\Models\Files;
use App\Models\User;
use App\Services\QuizService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the PDF filename and its source location.
        $pdfFilename = 'yourstudyfile.pdf';
        $pdfSourcePath = resource_path($pdfFilename);

        // Check that the file exists.
        if (!file_exists($pdfSourcePath)) {
            $this->command->error("The file {$pdfFilename} does not exist in the resources directory. Please add a valid PDF file.");
            return;
        }

        // Save the PDF to the public disk.
        Storage::disk('public')->put($pdfFilename, file_get_contents($pdfSourcePath));

        // Get the file's MIME type from the public disk.
        $mimeType = Storage::disk('public')->mimeType($pdfFilename);

        // Extract text from the PDF file.
        $extractedText = '';
        if (strpos($mimeType, 'pdf') !== false) {
            $parser = new Parser();
            $fullPath = storage_path('app/public/' . $pdfFilename);
            $pdf = $parser->parseFile($fullPath);
            $extractedText = $pdf->getText();
        } else {
            // Fallback: treat the file as plain text.
            $extractedText = Storage::disk('public')->get($pdfFilename);
        }

        // Use QuizService (which uses ChatGPT) to generate study notes from the extracted text.
        $quizService = new QuizService();
        $prompt = "Using ChatGPT, generate a concise summary and key study notes based on the following text:\n\n" . $extractedText;
        $studyNotes = $quizService->generateStudyNotes($prompt);

        // Retrieve an owner for the file record (for example, user with ID 1).
        $owner = User::query()->find(1);

        // Create the file record in the database with the generated study notes.
        Files::factory()->create([
            'owner_id'    => $owner->id,
            'name'        => $pdfFilename,
            'path'        => $pdfFilename,
            'study_notes' => $studyNotes,
            'is_ready'    => true,
            'type'        => $mimeType,
        ]);
    }
}
