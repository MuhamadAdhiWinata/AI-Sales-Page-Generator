<?php

namespace App\Http\Controllers;

use App\Models\SalesPage;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesPageController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->salesPages()->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'required|string',
            'features' => 'required|array',
            'target_audience' => 'required|string',
            'price' => 'required|string',
            'usp' => 'required|string',
            'tone' => 'required|string|in:Persuasif,Formal,Urgen',
        ]);

        $featuresStr = implode(', ', $request->features);
        
        $prompt = "Generate a high-converting sales page in HTML format using Tailwind CSS classes.
        Product Name: {$request->product_name}
        Description: {$request->product_description}
        Features: {$featuresStr}
        Target Audience: {$request->target_audience}
        Price: {$request->price}
        Unique Selling Point: {$request->usp}
        Tone: {$request->tone}
        
        Requirements:
        1. Use only Tailwind CSS classes for styling.
        2. Do not include <html>, <head>, or <body> tags. Just the inner content.
        3. Make it visually appealing with sections like Hero, Features, Pricing, and CTA.
        4. Use a {$request->tone} language style.
        5. Return ONLY the HTML code.";

        try {
            $result = Gemini::generativeModel(model: 'gemini-1.5-flash')
                ->generateContent($prompt);
            
            $contentHtml = $result->text();

            $salesPage = $request->user()->salesPages()->create([
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'features' => $request->features,
                'target_audience' => $request->target_audience,
                'price' => $request->price,
                'usp' => $request->usp,
                'content_html' => $contentHtml,
            ]);

            return response()->json($salesPage, 201);
        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate content: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, SalesPage $salesPage)
    {
        if ($salesPage->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($salesPage);
    }

    public function destroy(Request $request, SalesPage $salesPage)
    {
        if ($salesPage->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $salesPage->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
