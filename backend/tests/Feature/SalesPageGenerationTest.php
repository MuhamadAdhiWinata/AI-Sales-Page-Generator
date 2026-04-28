<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SalesPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\GenerateSalesPageJob;
use Tests\TestCase;

class SalesPageGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_start_sales_page_generation(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $payload = [
            'product_name' => 'Kopi Wonogiri Premium',
            'product_description' => 'Biji kopi pilihan yang diproses secara organik.',
            'features' => ['High Caffeine', 'Organic'],
            'target_audience' => 'Pekerja kantoran',
            'price' => 'Rp 75.000',
            'usp' => 'Aroma melati alami',
            'tone' => 'Persuasif',
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/sales-pages', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'status', 'product_name']);

        $this->assertDatabaseHas('sales_pages', [
            'product_name' => 'Kopi Wonogiri Premium',
            'status' => 'processing',
            'user_id' => $user->id,
        ]);

        Queue::assertPushed(GenerateSalesPageJob::class);
    }

    public function test_can_check_generation_status(): void
    {
        $user = User::factory()->create();
        $salesPage = SalesPage::create([
            'user_id' => $user->id,
            'product_name' => 'Test Product',
            'product_description' => 'Description',
            'features' => ['Feature'],
            'target_audience' => 'Audience',
            'price' => 'Price',
            'usp' => 'USP',
            'status' => 'completed',
            'content_html' => '<h1>Completed Content</h1>'
        ]);

        $response = $this->actingAs($user)
            ->getJson("/api/sales-pages/{$salesPage->id}/status");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'completed',
                'content_html' => '<h1>Completed Content</h1>'
            ]);
    }
}
