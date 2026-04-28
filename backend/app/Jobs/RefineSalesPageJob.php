<?php

namespace App\Jobs;

use App\Models\SalesPage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefineSalesPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $salesPage;
    public $instruction;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(SalesPage $salesPage, string $instruction)
    {
        $this->salesPage = $salesPage;
        $this->instruction = $instruction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentHtml = $this->salesPage->content_html;

        $prompt = "You are an expert Web Editor. You are given an existing HTML Landing Page and a specific instruction for modification.
        
        Current HTML:
        {$currentHtml}

        User Instruction:
        {$this->instruction}

        Your Task:
        1. Modify the HTML based EXACTLY on the user instruction.
        2. Keep all other parts of the HTML, CSS classes, and structure UNCHANGED unless requested.
        3. Do NOT add any conversational text.
        4. Return ONLY the complete modified HTML starting with <!DOCTYPE html>.
        5. Ensure the resulting HTML is still valid and functional.
        6. Preserve the <script> for smooth scrolling and all Google Fonts links.";

        try {
            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'HTTP-Referer' => env('APP_URL'),
                'X-Title' => 'AI Sales Page Refiner',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => env('OPENROUTER_MODEL', 'openrouter/free'),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            if ($response->failed()) {
                throw new \Exception('OpenRouter API request failed: ' . $response->body());
            }

            $contentHtml = $response->json('choices.0.message.content');
            
            // Clean AI chatter
            $contentHtml = preg_replace('/^```html\s+/i', '', $contentHtml);
            $contentHtml = preg_replace('/^```\s+/i', '', $contentHtml);
            $contentHtml = preg_replace('/\s+```$/', '', $contentHtml);

            $docStart = stripos($contentHtml, '<!DOCTYPE');
            if ($docStart !== false) {
                $contentHtml = substr($contentHtml, $docStart);
            }

            $this->salesPage->update([
                'content_html' => trim($contentHtml),
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error('AI Refinement Failed: ' . $e->getMessage());
            $this->salesPage->update([
                'status' => 'failed',
                'error_message' => 'Refinement error: ' . $e->getMessage(),
            ]);
        }
    }
}
