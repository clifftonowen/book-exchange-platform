<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Import the HTTP client facade
use App\Models\User; // For type hinting
use App\Models\Book; // For user's books data
use Illuminate\Support\Facades\Log; // Import the Log facade

class RecommendationController extends Controller
{
    /**
     * Generate book recommendations using OpenAI.
     *
     * @param \App\Models\User $user The user for whom to generate recommendations.
     * @return array An array of recommended books (title and author).
     */
    public static function generate(User $user): array
    {
        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            // Log this error in production for debugging, return empty array for POC
            Log::error('OPENAI_API_KEY not set in .env file.');
            return [];
        }

        // Determine user's preferred genres based on their listed books
        // IMPORTANT: This assumes you have a 'genre' column in your 'books' table.
        // If not, you can simplify the prompt to just suggest general popular books.
        $userGenres = $user->books->pluck('genre')->filter()->unique()->implode(', ');
        
        $prompt = '';
        if (!empty($userGenres)) {
            $prompt = "Suggest 3 highly-rated fiction books for someone who enjoys " . $userGenres . ". Provide title and author only, one book per line. No extra text or numbering.";
        } else {
            $prompt = "Suggest 3 highly-rated popular fiction books. Provide title and author only, one book per line. No extra text or numbering.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo', // You can use 'gpt-4o' or other models if available/preferred
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 100, // Limit response length to avoid excessive tokens
                'temperature' => 0.7, // Controls randomness: 0.0 for deterministic, 1.0 for very creative
            ])->json();

            // Check for API errors reported by OpenAI
            if (isset($response['error'])) {
                Log::error('OpenAI API Error: ' . $response['error']['message']);
                return [];
            }

            // Check if response contains the expected content
            if (!isset($response['choices'][0]['message']['content'])) {
                Log::warning('OpenAI API response missing expected content.');
                return [];
            }

            $rawRecommendations = $response['choices'][0]['message']['content'];

            // Parse the recommendations (assuming "Title: Author" or "Title by Author" format per line)
            $recommendations = [];
            $lines = explode("\n", trim($rawRecommendations));
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Attempt to parse "Title by Author" or "Title: Author"
                if (preg_match('/^(.*?)\s*by\s*(.+)$/i', $line, $matches) || preg_match('/^(.*?):\s*(.+)$/', $line, $matches)) {
                    $recommendations[] = [
                        'title' => trim($matches[1]),
                        'author' => trim($matches[2])
                    ];
                } else {
                    // Fallback for unexpected formats: assume entire line is title
                    $recommendations[] = ['title' => $line, 'author' => 'Unknown Author'];
                }
            }
            return $recommendations;

        } catch (\Exception $e) {
            // Catch any network or other exceptions during the API call
            Log::error('Exception during OpenAI API call: ' . $e->getMessage());
            return [];
        }
    }
}