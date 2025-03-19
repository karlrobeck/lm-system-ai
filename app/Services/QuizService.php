<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Files;
use App\Models\ModalityAuditory;
use App\Models\ModalityKinesthetic;
use App\Models\ModalityReading;
use App\Models\ModalityVisualization;
use App\Models\ModalityWriting;
use App\Models\Scores;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenAI;

class QuizService
{
    private $apiKey;
    private $client;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');

        Log::info('OpenAI API key', ['key' => $this->apiKey]);

        if (!$this->apiKey) {
            throw new \Exception('OpenAI API key not set.');
        }

        $this->client = OpenAI::client($this->apiKey);
    }

    public function generate_test(string $content, User $user, Files $file, $test_type)
    {

        $prompt = "
            you are a expert at teaching. You are tasked with generating different types of educational questions and activities. For each of the following five categories, generate exactly 5 items. Each output must be a JSON array of 5 objects with no extra text or markdown. Follow the specific field guidelines for each category:
            1. **reading**  
            - **Context:** Questions must be based on the provided reading text.  
            - **Fields for each object:**  
                - 'question_index' (integer)  
                - 'correct_answer' (string)  
                - 'choices' (array of 4 strings)  // use sentence based choices
                - 'question' (string)  
                - 'test_type' (either 'pre' or 'post')  

            2. **writing**  
            - **Context:** Generate prompts for a {$test_type}-test.  
            - **Fields for each object:**  
                - 'question_index' (integer)  
                - 'context_answer' (string)  
                - 'question' (string)  
                - 'test_type' (either 'pre' or 'post')  

            3. **auditory**  
            - **Context:** Generate questions for a {$test_type}-test.  
            - **Fields for each object:**  
                - 'question_index' (integer)  
                - 'correct_answer' (string)  
                - 'choices' (array of 4 strings)  // use sentence based choices
                - 'question' (string)  
                - 'test_type' (either 'pre' or 'post')  

            4. **kinesthetic**  
            - **Context:** Generate activities for a {$test_type}-test.  
            - **Fields for each object:**  
                - question_index (integer)  
                - context_answer (string)  
                - question (string)  
                - test_type (either 'pre' or 'post')  

            5. **visualization**  
            - **Context:** Generate questions for a {$test_type}-test.  
            - **Fields for each object:**  
                - 'question_index' (integer)  
                - 'image_prompt' (string suitable for generating an image with DALL-E, maximum of 600 tokens)  
                - 'choices' (array of 4 strings)  // use sentence based choices
                - 'correct_answer' (string)  
                - 'question' (string)  
                - 'test_type' (either 'pre' or 'post')  

            Remember:  
            - Output only a JSON array of 5 objects per category.  
            - Do not include any additional text or markdown in your response.     
        ";

        $jsonSchema = [
            "name" => "test_response",
            "strict" => true,
            'schema' => [
                "type" => "object",
                "properties" => [
                    "reading" => [
                        "type" => "array",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "question_index" => ["type" => "integer"],
                                "correct_answer" => ["type" => "string"],
                                "choices" => [
                                    "type" => "array",
                                    "items" => ["type" => "string"]
                                ],
                                "question" => ["type" => "string"],
                                "test_type" => ["type" => "string"]
                            ],
                            "required" => [
                                "question_index",
                                "correct_answer",
                                "choices",
                                "question",
                                "test_type"
                            ],
                            "additionalProperties" => false
                        ],
                        "additionalProperties" => false
                    ],
                    "writing" => [
                        "type" => "array",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "question_index" => ["type" => "integer"],
                                "context_answer" => ["type" => "string"],
                                "question" => ["type" => "string"],
                                "test_type" => ["type" => "string"]
                            ],
                            "required" => [
                                "question_index",
                                "context_answer",
                                "question",
                                "test_type"
                            ],
                            "additionalProperties" => false
                        ],
                        "additionalProperties" => false
                    ],
                    "auditory" => [
                        "type" => "array",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "question_index" => ["type" => "integer"],
                                "correct_answer" => ["type" => "string"],
                                "choices" => [
                                    "type" => "array",
                                    "items" => ["type" => "string"]
                                ],
                                "question" => ["type" => "string"],
                                "test_type" => ["type" => "string"]
                            ],
                            "required" => [
                                "question_index",
                                "correct_answer",
                                "choices",
                                "question",
                                "test_type"
                            ],
                            "additionalProperties" => false
                        ],
                        "additionalProperties" => false
                    ],
                    "kinesthetic" => [
                        "type" => "array",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "question_index" => ["type" => "integer"],
                                "context_answer" => ["type" => "string"],
                                "question" => ["type" => "string"],
                                "test_type" => ["type" => "string"]
                            ],
                            "required" => [
                                "question_index",
                                "context_answer",
                                "question",
                                "test_type"
                            ],
                            "additionalProperties" => false
                        ],
                        "additionalProperties" => false
                    ],
                    "visualization" => [
                        "type" => "array",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "question_index" => ["type" => "integer"],
                                "image_prompt" => ["type" => "string"],
                                "choices" => [
                                    "type" => "array",
                                    "items" => ["type" => "string"]
                                ],
                                "correct_answer" => ["type" => "string"],
                                "question" => ["type" => "string"],
                                "test_type" => ["type" => "string"]
                            ],
                            "required" => [
                                "question_index",
                                "image_prompt",
                                "choices",
                                "correct_answer",
                                "question",
                                "test_type"
                            ],
                            "additionalProperties" => false
                        ],
                        "additionalProperties" => false
                    ]
                ],
                "required" => [
                    "reading",
                    "writing",
                    "auditory",
                    "kinesthetic",
                    "visualization"
                ],
                "additionalProperties" => false
            ]
        ];

        try {
            $result = $this->client->chat()->create([
                'model'    => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => $content]
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => $jsonSchema
                ]
            ]);

            Log::info('Generated test', ['result' => $result]);

            $responseData = json_decode($result['choices'][0]['message']['content'], true);

            foreach ($responseData['reading'] as $reading) {
                $reading['choices'] = json_encode($reading['choices']);
                ModalityReading::create(array_merge($reading, ['file_id' => $file->id]));
            }

            foreach ($responseData['writing'] as $writing) {
                ModalityWriting::create(array_merge($writing, ['file_id' => $file->id]));
            }

            foreach ($responseData['auditory'] as $auditory) {
                $auditory['choices'] = json_encode($auditory['choices']);
                ModalityAuditory::create(array_merge($auditory, ['file_id' => $file->id]));
            }

            foreach ($responseData['kinesthetic'] as $kinesthetic) {
                ModalityKinesthetic::create(array_merge($kinesthetic, ['file_id' => $file->id]));
            }

            foreach ($responseData['visualization'] as $visualization) {
                $visualization['choices'] = json_encode($visualization['choices']);
                ModalityVisualization::create(array_merge($visualization, ['file_id' => $file->id]));
            }
        } catch (\Exception $e) {
            Log::error('Error generating test: ' . $e->getMessage());
            return null;
        }
    }

    public function checkSubmission($system_prompt, $user_prompt)
    {

        $schema = [
            "name" => "check_response",
            "strict" => true,
            "schema" => [
                "type" => "object",
                "properties" => [
                    "correct" => [
                        "type" => "integer"
                    ],
                    "total" => [
                        "type" => "integer"
                    ],
                    "is_passed" => [
                        "type" => "boolean"
                    ],
                ],
                "required" => [
                    "correct",
                    "total",
                    "is_passed"
                ],
                "additionalProperties" => false
            ]
        ];

        $response = $this->client->chat()->create([
            'model'    => 'gpt-4o-2024-08-06',
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                ['role' => 'user', 'content' => $user_prompt]
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $schema
            ]
        ]);

        $responseData = $response;

        if (isset($responseData['choices'][0]['message']['content'])) {
            $assistant_reply = $responseData['choices'][0]['message']['content'];
            return $assistant_reply;
        } else {
            Log::error('Unexpected response structure from GPT API.', [
                'response' => $responseData,
            ]);
            return null;
        }
    }

    public function generateAssessment(User $user)
    {

        $prompt = '
            You are an expert at analyzing learning styles. Based on the following content, generate an assessment for the user with the following fields:\n- rank (integer)\n- name (string, must be one of the following: reading, writing, auditory, kinesthetic, visualization)\n- message (string)\nProvide the response in JSON format. you are not allowed to send any markdown. use only JSON format.\n
                based on this user\'s JSON response. return a json object with the following. \n NOTE: do not tie the result because it will confuse the backend. ONLY RETURN JSON no other messages\n"
                . `-- GPT JSON response
                    [
                    {
                        "rank":"<rank of the modality>",
                        "name":"<name of the modality>", // must be one of the following: reading, writing, auditory, kinesthetic, visualization
                        "message":"<GPT message that will tell the user why its in this rank>"
                    }
                ]
        ';

        $jsonSchema =  [
            "name" => "assessment_response",
            "strict" => true,
            "schema" => [
                "type" => "object",
                "properties" => [
                    "ranks" => [
                        "type" => "array",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "name" => [
                                    "type" => "string"
                                ],
                                "rank" => [
                                    "type" => "integer"
                                ],
                                "message" => [
                                    "type" => "string"
                                ]
                            ],
                            "required" => [
                                "name",
                                "rank",
                                "message"
                            ],
                            "additionalProperties" => false
                        ]
                    ]
                ],
                "required" => [
                    "ranks"
                ],
                "additionalProperties" => false
            ]
        ];

        $response = $this->client->chat()->create([
            'model'    => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $prompt],
                ['role' => 'user', 'content' => $user['assessment_content']]
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => $jsonSchema,
            ]
        ]);

        $responseData = json_decode($response['choices'][0]['message']['content'], true);

        foreach ($responseData['ranks'] as $data) {
            Assessment::create([
                'user_id' => $user->id,
                'modality' => $data['name'],
                'rank' => $data['rank'],
                'message' => $data['message']
            ]);
        }
    }

    public function generateStudyNotes(string $user_prompt)
    {

        $system_prompt = 'You are an expert at creating study notes. create key notes that the user can used to study based on the user prompt. do not create tests questions only keynotes. only return markdown format\n';

        $response = $this->client->chat()->create([
            'model'    => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                ['role' => 'user', 'content' => $user_prompt]
            ]
        ]);

        $responseData = $response['choices'][0]['message']['content'];

        return $responseData;
    }

    public function generateImage(string $image_prompt)
    {
        $response = $this->client->images()->create([
            'model' => 'dall-e-2',
            'prompt' => $image_prompt,
            'n'      => 1,
            'size'   => '512x512',
            'response_format' => 'url',
        ]);

        $responseData = $response['data'];

        Log::info('Generated image', ['response' => $responseData]);

        if (isset($responseData[0]['url'])) {
            return $responseData[0]['url'];
        } else {
            return null;
        }
    }
}
