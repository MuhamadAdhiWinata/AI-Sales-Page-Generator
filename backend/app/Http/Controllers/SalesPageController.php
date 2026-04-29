<?php

namespace App\Http\Controllers;

use App\Models\SalesPage;
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
            'template' => 'required|string|in:classic,neon,pastel',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->product_name) . '-' . \Illuminate\Support\Str::random(5);

        $salesPage = $request->user()->salesPages()->create([
            'product_name' => $request->product_name,
            'slug' => $slug,
            'product_description' => $request->product_description,
            'features' => $request->features,
            'target_audience' => $request->target_audience,
            'price' => $request->price,
            'usp' => $request->usp,
            'status' => 'processing',
            'template' => $request->template,
        ]);

        \App\Jobs\GenerateSalesPageJob::dispatch($salesPage, $request->tone);

        return response()->json($salesPage, 201);
    }

    public function status(SalesPage $salesPage)
    {
        return response()->json([
            'status' => $salesPage->status,
            'slug' => $salesPage->slug,
            'content_html' => $salesPage->content_html,
            'error_message' => $salesPage->error_message,
        ]);
    }

    public function getBySlug($slug)
    {
        $salesPage = SalesPage::where('slug', $slug)->firstOrFail();
        
        if ($salesPage->status !== 'completed') {
            return response()->json(['error' => 'Sales page is still processing.'], 404);
        }

        return response()->json([
            'product_name' => $salesPage->product_name,
            'content_html' => $salesPage->content_html
        ]);
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

    public function refine(Request $request, SalesPage $salesPage)
    {
        if ($salesPage->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'instruction' => 'required|string|max:1000',
        ]);

        $salesPage->update([
            'status' => 'processing',
            'error_message' => null,
        ]);

        \App\Jobs\RefineSalesPageJob::dispatch($salesPage, $request->instruction);

        return response()->json([
            'message' => 'Refinement started',
            'sales_page' => $salesPage
        ]);
    }
}
