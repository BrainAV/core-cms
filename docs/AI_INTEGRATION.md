# 🧠 AI Integration Strategy

## 1. Vision: The "AI-Native" CMS
Core CMS aims to integrate Artificial Intelligence directly into the kernel, acting as the "Brain" of the system. Unlike plugins that tack on AI features, our goal is to have an `AIService` available globally to the Core and all Plugins.

## 2. Architecture: The Driver Pattern
To support multiple AI providers (Cloud and Local), we will use a **Driver/Adapter Pattern**.

*   **`AIService` (The Manager)**: The main class that the rest of the CMS interacts with. It handles configuration and loads the correct driver.
*   **`AIProviderInterface` (The Contract)**: Defines the methods every driver must implement (e.g., `generateText($prompt)`, `chat($messages)`).
*   **Drivers**:
    *   `GeminiDriver`: Connects to Google Gemini API.
    *   `OpenAIDriver`: Connects to OpenAI API.
    *   `LocalDriver`: Connects to local LLMs (Ollama, LM Studio) via standard endpoints.

## 3. Configuration
Settings will be managed in **Admin > Settings > AI Configuration**.

| Option Name | Description | Example |
| :--- | :--- | :--- |
| `ai_provider` | The active driver. | `gemini`, `openai`, `local` |
| `ai_api_key` | The secret key. | `AIzaSy...` |
| `ai_model` | The specific model to use. | `gemini-1.5-flash`, `gpt-4o`, `llama3` |
| `ai_base_url` | Custom endpoint (for Local/Enterprise). | `http://localhost:11434/v1` |

## 4. Features (Phase 5)

### A. Admin Copilot
*   **Location**: Post Editor (`admin/post-edit.php`).
*   **Function**: "Draft this for me", "Fix grammar", "Generate SEO Title".
*   **Implementation**: JavaScript button sends current content to `admin/api/ai.php`, which calls `AIService`.

### B. Frontend Assistant
*   **Location**: Site Widget.
*   **Function**: Answer visitor questions based on site content.
*   **Implementation**: RAG (Retrieval-Augmented Generation) using the `posts` table as knowledge.

## 5. Security Considerations
*   **API Keys**: Stored in the database (`options` table). In future versions, we may support defining these in `config.php` for higher security.
*   **Rate Limiting**: The API endpoint should implement basic rate limiting to prevent abuse.