<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Quote;
use App\Models\User;
use App\Models\UserQuote;
use App\Services\QuoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $quoteService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quoteService = Mockery::mock(QuoteService::class);
        $this->app->instance(QuoteService::class, $this->quoteService);
    }

    public function test_it_returns_a_quote_if_user_has_one_for_today()
    {
        $user = User::factory()->create();
        $quoteText = 'Inspirational Quote';

        $category = Category::create(['name' => 'inspire']);

        $currentDateTime = Carbon::now();
        $quoteRecord = Quote::create([
            'quote' => $quoteText,
            'author' => 'Author Name',
            'length' => 100,
            'language' => 'en',
            'tags' => json_encode(['inspire']),
            'permalink' => 'http://example.com',
            'title' => 'Title',
            'background' => 'http://example.com/background.jpg',
            'date' => $currentDateTime,
            'category_id' => $category->id,
            'api_id' => 'unique-api-id',
        ]);

        UserQuote::create([
            'user_id' => $user->id,
            'quote_id' => $quoteRecord->id,
            'created_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/quote-of-the-day');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'quote' => $quoteText,
                'author' => 'Author Name',
                'length' => 100,
                'language' => 'en',
                'permalink' => 'http://example.com',
                'title' => 'Title',
                'background' => 'http://example.com/background.jpg',
                'category_id' => $category->id,
                'api_id' => 'unique-api-id',
            ]);
    }

    public function test_it_returns_a_quote_if_no_quote_for_today_and_no_favorite_categories()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'inspire']);
        $quoteText = 'Inspirational Quote';

        $quoteRecord = Quote::create([
            'quote' => $quoteText,
            'author' => 'Author Name',
            'length' => 100,
            'language' => 'en',
            'tags' => json_encode(['inspire']),
            'permalink' => 'http://example.com',
            'title' => 'Title',
            'background' => 'http://example.com/background.jpg',
            'date' => Carbon::now()->toDateString(),
            'category_id' => $category->id, // Use the valid category ID
            'api_id' => 'unique-api-id',
        ]);

        $this->quoteService->shouldReceive('getQuoteOfTheDay')
            ->once()
            ->with('inspire')
            ->andReturn($quoteRecord);

        $response = $this->actingAs($user, 'sanctum')->call('GET', '/api/quote-of-the-day');

        $response->assertStatus(200)
            ->assertJson([
                'quote' => $quoteText,
            ]);

        $this->assertDatabaseHas('user_quotes', [
            'user_id' => $user->id,
            'quote_id' => $quoteRecord->id,
        ]);
    }

    public function test_it_returns_a_quote_based_on_favorite_category()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'inspire']);
        $user->favoriteCategories()->attach($category->id);
        $quoteText = 'Inspirational Quote';

        $quoteRecord = Quote::create([
            'quote' => $quoteText,
            'author' => 'Author Name',
            'length' => 100,
            'language' => 'en',
            'tags' => json_encode(['inspire']),
            'permalink' => 'http://example.com',
            'title' => 'Title',
            'background' => 'http://example.com/background.jpg',
            'date' => Carbon::now()->toDateString(),
            'category_id' => $category->id, // Use the valid category ID
            'api_id' => 'unique-api-id',
        ]);

        $this->quoteService->shouldReceive('getQuoteOfTheDay')
            ->once()
            ->with('inspire')
            ->andReturn($quoteRecord);

        $response = $this->actingAs($user, 'sanctum')->call('GET', '/api/quote-of-the-day');

        $response->assertStatus(200)
            ->assertJson([
                'quote' => $quoteText,
            ]);

        $this->assertDatabaseHas('user_quotes', [
            'user_id' => $user->id,
            'quote_id' => $quoteRecord->id,
        ]);
    }

    public function test_it_returns_an_error_when_service_returns_no_quote()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'inspire']);

        $this->quoteService->shouldReceive('getQuoteOfTheDay')
            ->once()
            ->with('inspire')
            ->andReturn(null);

        $response = $this->actingAs($user, 'sanctum')->call('GET', '/api/quote-of-the-day');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'No quote found for today. Please try again later.',
            ]);
    }
}
