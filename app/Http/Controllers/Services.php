<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuizService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');

        if (!$this->apiKey) {
            throw new \Exception('OpenAI API key not set.');
        }
    }

    public function create_reading_modality($content, $test_type)
    {
        return $this->generate_test($content, 'reading', $test_type);
    }

    public function create_writing_modality($content, $test_type)
    {
        return $this->generate_test($content, 'writing', $test_type);
    }

    public function create_auditory_modality($content, $test_type)
    {
        return $this->generate_test($content, 'auditory', $test_type);
    }

    public function create_kinesthetic_modality($content, $test_type)
    {
        return $this->generate_test($content, 'kinesthetic', $test_type);
    }

    public function create_visualization_modality($content, $test_type)
    {
        return $this->generate_test($content, 'visualization', $test_type);
    }

    private function generate_test($content, $modality, $test_type)
    {
        $system_prompts = [
            'reading' => "You are an expert at creating multiple-choice reading comprehension questions. "
                . "Based on the following content, generate a {$test_type}-test question with the following fields:\n"
                . "- question_index (integer)\n"
                . "- correct_answer (string)\n"
                . "- choices (list of strings with a maximum of 4 choices)\n"
                . "- question (string)\n"
                . "- test_type (string, either 'pre' or 'post')\n"
                . "Provide the response in JSON format.",
            'writing' => "You are an expert at creating writing prompts. "
                . "Based on the following content, generate a {$test_type}-test writing prompt with the following fields:\n"
                . "- question_index (integer)\n"
                . "- correct_answer (string)\n"
                . "- question (string)\n"
                . "- test_type (string, either 'pre' or 'post')\n"
                . "Provide the response in JSON format.",
            'auditory' => "You are an expert at creating auditory comprehension questions. "
                . "Based on the following content, generate a {$test_type}-test question with the following fields:\n"
                . "- question_index (integer)\n"
                . "- correct_answer (string)\n"
                . "- choices (list of strings with a maximum of 4 choices)\n"
                . "- question (string)\n"
                . "- test_type (string, either 'pre' or 'post')\n"
                . "Provide the response in JSON format.",
            'kinesthetic' => "You are an expert at creating kinesthetic learning activities. "
                . "Based on the following content, generate a {$test_type}-test activity with the following fields:\n"
                . "- question_index (integer)\n"
                . "- correct_answer (string)\n"
                . "- question (string)\n"
                . "- test_type (string, either 'pre' or 'post')\n"
                . "Provide the response in JSON format.",
            'visualization' => "You are an expert at creating visualization-based questions. "
                . "Based on the following content, generate a {$test_type}-test question with the following fields:\n"
                . "- question_index (integer)\n"
                . "- image_prompt (string, suitable for generating an image with DALL-E)\n"
                . "- choices (list of strings with a maximum of 4 choices)\n"
                . "- correct_answer (string)\n"
                . "- question (string)\n"
                . "- test_type (string, either 'pre' or 'post')\n"
                . "Provide the response in JSON format.",
        ];

        $system_prompt = $system_prompts[$modality];

        $messages = [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $content]
        ];

        $url = 'https://api.openai.com/v1/chat/completions';
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey
        ];
        $data = [
            'model'    => 'gpt-3.5-turbo',
            'messages' => $messages
        ];

        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            Log::error('Failed to get response from OpenAI GPT API.', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        }

        $responseData = $response->json();
        if (isset($responseData['choices'][0]['message']['content'])) {
            $assistant_reply = $responseData['choices'][0]['message']['content'];

            $data = json_decode($assistant_reply, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // For visualization modality, generate the image
                if ($modality === 'visualization' && isset($data['image_prompt'])) {
                    $data['image_url'] = $this->generateImage($data['image_prompt']);
                }
                return $data;
            } else {
                // Try to extract JSON from the assistant's reply
                if (preg_match('/\{.*\}/s', $assistant_reply, $matches)) {
                    $json_str = $matches[0];
                    $data = json_decode($json_str, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // For visualization modality, generate the image
                        if ($modality === 'visualization' && isset($data['image_prompt'])) {
                            $data['image_url'] = $this->generateImage($data['image_prompt']);
                        }
                        return $data;
                    } else {
                        Log::error('JSON decoding error: ' . json_last_error_msg(), [
                            'assistant_reply' => $assistant_reply,
                        ]);
                        return null;
                    }
                } else {
                    Log::error('No JSON object found in the assistant\'s reply.', [
                        'assistant_reply' => $assistant_reply,
                    ]);
                    return null;
                }
            }
        } else {
            Log::error('Unexpected response structure from GPT API.', [
                'response' => $responseData,
            ]);
            return null;
        }
    }

    private function generateImage($image_prompt)
    {
        $url = 'https://api.openai.com/v1/images/generations';

        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];

        $data = [
            'prompt' => $image_prompt,
            'n'      => 1,
            'size'   => '512x512',
        ];

        $response = Http::withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            Log::error('Failed to get response from OpenAI DALL·E API.', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        }

        $responseData = $response->json();

        if (isset($responseData['data'][0]['url'])) {
            return $responseData['data'][0]['url'];
        } else {
            Log::error('Unexpected response structure from DALL·E API.', [
                'response' => $responseData,
            ]);
            return null;
        }
    }
}