<?php

namespace App\Jobs;

use App\Models\SalesPage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateSalesPageJob implements ShouldQueue
{
    use Queueable;

    protected $salesPage;
    protected $tone;

    /**
     * Create a new job instance.
     */
    public function __construct(SalesPage $salesPage, string $tone)
    {
        $this->salesPage = $salesPage;
        $this->tone = $tone;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $featuresStr = is_array($this->salesPage->features) ? implode(', ', $this->salesPage->features) : $this->salesPage->features;

        $templateFile = 'refrensi.html';
        if ($this->salesPage->template === 'neon') {
            $templateFile = 'refrensi_neon.html';
        } elseif ($this->salesPage->template === 'pastel') {
            $templateFile = 'refrensi_pastel.html';
        }
        $templatePath = base_path($templateFile);
        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found at: " . $templatePath);
        }
        $template = file_get_contents($templatePath);

        $prompt = "You are a professional web editor. Your task is to update the provided HTML Template with new product information while keeping the layout, CSS classes, and structure EXACTLY the same.

        New Product Data:
        - Product Name: {$this->salesPage->product_name}
        - Description: {$this->salesPage->product_description}
        - Features: {$featuresStr}
        - Price: {$this->salesPage->price}
        - Tone: {$this->tone} (Indonesian)

        Instructions:
        1. Use the provided HTML Template below.
        2. Replace the placeholder text (Brand Name, Hero Title, Descriptions, Features, Menu Items, etc.) with the new product data.
        3. NAVIGATION & LINKS: You MUST ensure that the 'href' attributes in the <nav> (e.g., #home, #about, #menu, #contact) match the 'id' attributes of the sections EXACTLY. Do not change these IDs.
        4. SCRIPT PRESERVATION: Do NOT remove or modify the <script> tag inside the <head> that handles smooth scrolling.
        5. Keep all Tailwind CSS classes, Google Font links, and HTML structure identical to the template.
        6. NO CHATTER: Do NOT include any introductory or concluding remarks. Do NOT say 'Here is your HTML' or 'I have updated the template'. Return ONLY the complete HTML code starting with <!DOCTYPE html>.
        7. Return ONLY the complete modified HTML.

        HTML Template to edit:
        {$template}";

        try {
            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'HTTP-Referer' => env('APP_URL'),
                'X-Title' => 'AI Sales Page Generator',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => env('OPENROUTER_MODEL', 'openrouter/free'),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 4000,
            ]);

            if ($response->failed()) {
                throw new \Exception($response->body());
            }

            $contentHtml = $response->json('choices.0.message.content');
            
            // Aggressively strip markdown code blocks and conversational text
            $contentHtml = preg_replace('/^```html\s+/i', '', $contentHtml);
            $contentHtml = preg_replace('/^```\s+/i', '', $contentHtml);
            $contentHtml = preg_replace('/\s+```$/', '', $contentHtml);

            // Find the start of the actual HTML document and strip anything before it
            $docStart = stripos($contentHtml, '<!DOCTYPE');
            if ($docStart !== false) {
                $contentHtml = substr($contentHtml, $docStart);
            }

            $this->salesPage->update([
                'content_html' => trim($contentHtml),
                'status' => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error('AI Generation Failed: ' . $e->getMessage());
            $this->salesPage->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
