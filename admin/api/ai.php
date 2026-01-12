<?php
/**
 * API Endpoint: AI Copilot
 */
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/ai.php';

header('Content-Type: application/json');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => 0, 'error' => 'Unauthorized']);
    exit;
}

// Get JSON Input
$data = json_decode(file_get_contents('php://input'), true);
$prompt = $data['prompt'] ?? '';

if (empty($prompt)) {
    echo json_encode(['success' => 0, 'error' => 'No prompt provided']);
    exit;
}

try {
    $ai = AIService::getProvider();
    
    // System Instruction: Force JSON output compatible with Editor.js
    $system_instruction = "You are a CMS content assistant. Output your response strictly as a JSON array of Editor.js blocks.
Supported blocks:
1. Header: {\"type\": \"header\", \"data\": {\"text\": \"...\", \"level\": 2}}
2. Paragraph: {\"type\": \"paragraph\", \"data\": {\"text\": \"...\"}}
3. List: {\"type\": \"list\", \"data\": {\"style\": \"unordered\", \"items\": [\"item1\", \"item2\"]}}
Do not include markdown formatting (like **bold**) inside the text, use <b> or <i> tags if needed.
Do not wrap the response in markdown code blocks (```json). Just return the raw JSON array.
User Prompt: ";

    $response = $ai->generateText($system_instruction . $prompt);
    
    // Attempt to extract JSON array if the AI was chatty or included preamble
    // Look for the first '[' that is followed by a '{' (start of block array)
    if (preg_match('/\[\s*\{/', $response, $matches, PREG_OFFSET_CAPTURE)) {
        $start = $matches[0][1];
        $end = strrpos($response, ']');
        if ($end !== false && $end > $start) {
            $response = substr($response, $start, $end - $start + 1);
        }
    } 
    // Else: No JSON array found, return raw response (will likely fail JSON.parse and insert as text)

    echo json_encode(['success' => 1, 'text' => trim($response)]);
} catch (Exception $e) {
    echo json_encode(['success' => 0, 'error' => $e->getMessage()]);
}