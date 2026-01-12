<?php
/**
 * AI Service & Drivers
 * 
 * Handles interactions with AI providers (Gemini, OpenAI, Local).
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

// --- Interface ---
interface AIProviderInterface {
    public function generateText(string $prompt): string;
}

// --- Drivers ---

class GeminiDriver implements AIProviderInterface {
    private $apiKey;
    private $model;
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct($apiKey, $model) {
        $this->apiKey = $apiKey;
        $this->model = $model ?: 'gemini-1.5-flash';
    }

    public function generateText(string $prompt): string {
        $url = $this->baseUrl . $this->model . ':generateContent?key=' . $this->apiKey;
        $data = [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ];

        $response = $this->makeRequest($url, $data);
        
        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            return $response['candidates'][0]['content']['parts'][0]['text'];
        }
        
        throw new Exception("Gemini API Error: " . json_encode($response));
    }

    private function makeRequest($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
             throw new Exception("Curl Error: " . curl_error($ch));
        }
        
        curl_close($ch);
        return json_decode($result, true);
    }
}

class OpenAIDriver implements AIProviderInterface {
    private $apiKey;
    private $model;
    private $baseUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct($apiKey, $model) {
        $this->apiKey = $apiKey;
        $this->model = $model ?: 'gpt-4o';
    }

    public function generateText(string $prompt): string {
        $data = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ];

        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
             throw new Exception("Curl Error: " . curl_error($ch));
        }

        curl_close($ch);
        $response = json_decode($result, true);

        if (isset($response['choices'][0]['message']['content'])) {
            return $response['choices'][0]['message']['content'];
        }

        throw new Exception("OpenAI API Error: " . json_encode($response));
    }
}

class LocalDriver implements AIProviderInterface {
    private $baseUrl;
    private $model;

    public function __construct($baseUrl, $model) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->model = $model ?: 'llama3';
    }

    public function generateText(string $prompt): string {
        // Assumes OpenAI-compatible endpoint (e.g., /v1/chat/completions)
        $url = $this->baseUrl;
        if (strpos($url, 'chat/completions') === false) {
             $url .= '/chat/completions';
        }
        
        $data = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'stream' => false
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
             throw new Exception("Curl Error: " . curl_error($ch));
        }

        curl_close($ch);
        $response = json_decode($result, true);

        if (isset($response['choices'][0]['message']['content'])) {
            return $response['choices'][0]['message']['content'];
        }
        
        throw new Exception("Local AI Error: " . json_encode($response));
    }
}

// --- Service Factory ---

class AIService {
    public static function getProvider() {
        $provider = get_option('ai_provider', 'gemini');
        $apiKey = get_option('ai_api_key', '');
        $model = get_option('ai_model', '');
        $baseUrl = get_option('ai_base_url', '');

        switch ($provider) {
            case 'openai':
                return new OpenAIDriver($apiKey, $model);
            case 'local':
                return new LocalDriver($baseUrl, $model);
            case 'gemini':
            default:
                return new GeminiDriver($apiKey, $model);
        }
    }
}