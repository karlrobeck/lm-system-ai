private function generate_test($content, $modality, $test_type)
{
    $system_prompts = [
        'reading' => "You are an expert at creating multiple-choice reading comprehension questions. "
            . "Based on the following content, generate **15** questions for a {$test_type}-test with the following fields for each question:\n"
            . "- question_index (integer)\n"
            . "- correct_answer (string)\n"
            . "- choices (list of strings with a maximum of 4 choices)\n"
            . "- question (string)\n"
            . "- test_type (string, either 'pre' or 'post')\n"
            . "Ensure that there are **15 questions** total for this {$test_type}-test. Provide the response in JSON format. You are not allowed to send any markdown. Use only JSON format.\n",
        'writing' => "You are an expert at creating writing prompts."
            . "Based on the following content, generate **15** writing prompts for a {$test_type}-test with the following fields for each prompt:\n"
            . "- question_index (integer)\n"
            . "- context_answer (string)\n"
            . "- question (string)\n"
            . "- test_type (string, either 'pre' or 'post')\n"
            . "Ensure that there are **15 questions** total for this {$test_type}-test. Provide the response in JSON format. You are not allowed to send any markdown. Use only JSON format.",
        'auditory' => "You are an expert at creating auditory comprehension questions. "
            . "Based on the following content, generate **15** auditory comprehension questions for a {$test_type}-test with the following fields for each question:\n"
            . "- question_index (integer)\n"
            . "- correct_answer (string)\n"
            . "- choices (list of strings with a maximum of 4 choices)\n"
            . "- question (string)\n"
            . "- test_type (string, either 'pre' or 'post')\n"
            . "Ensure that there are **15 questions** total for this {$test_type}-test. Provide the response in JSON format. You are not allowed to send any markdown. Use only JSON format.",
        'kinesthetic' => "You are an expert at creating kinesthetic learning activities. "
            . "Based on the following content, generate **15** kinesthetic activities for a {$test_type}-test with the following fields for each activity:\n"
            . "- question_index (integer)\n"
            . "- context_answer (string)\n"
            . "- question (string)\n"
            . "- test_type (string, either 'pre' or 'post')\n"
            . "Ensure that there are **15 activities** total for this {$test_type}-test. Provide the response in JSON format. You are not allowed to send any markdown. Use only JSON format.",
        'visualization' => "You are an expert at creating visualization-based questions. "
            . "Based on the following content, generate **15** visualization-based questions for a {$test_type}-test with the following fields for each question:\n"
            . "- question_index (integer)\n"
            . "- image_prompt (string, suitable for generating an image with DALL-E) maximum of 600 tokens\n"
            . "- choices (list of strings with a maximum of 4 choices)\n"
            . "- correct_answer (string)\n"
            . "- question (string)\n"
            . "- test_type (string, either 'pre' or 'post')\n"
            . "Ensure that there are **15 questions** total for this {$test_type}-test. Provide the response in JSON format. You are not allowed to send any markdown. Use only JSON format.",
    ];

    $system_prompt = $system_prompts[$modality];

    if (empty($system_prompt)) {
        Log::error("No system prompt defined for modality: {$modality}");
        return null;
    }

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
