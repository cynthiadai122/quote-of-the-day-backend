<?php

namespace Tests\Feature\Jobs;

use App\Jobs\UpdateDailyQuotes;
use App\Models\Category;
use App\Models\Quote;
use App\Models\User;
use App\Services\QuoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

class UpdateDailyQuotesTest extends TestCase
{
    use RefreshDatabase;

    protected $quoteService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quoteService = Mockery::mock(QuoteService::class);
        $this->app->instance(QuoteService::class, $this->quoteService);

        // Disable queue for tests
        Queue::fake();
    }

    public function test_it_updates_daily_quotes_for_users()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'inspire']);
        $quote = Quote::create([
            'quote' => 'Inspirational Quote',
            'author' => 'Author Name',
            'length' => 100,
            'language' => 'en',
            'tags' => json_encode(['inspire']),
            'permalink' => 'http://example.com',
            'title' => 'Title',
            'background' => 'http://example.com/background.jpg',
            'date' => Carbon::now(),
            'category_id' => $category->id,
            'api_id' => 'unique-api-id',
        ]);

        $this->quoteService->shouldReceive('getQuoteOfTheDay')
            ->once()
            ->with('inspire')
            ->andReturn($quote);

        $job = new UpdateDailyQuotes($this->quoteService);
        $job->handle();

        $this->assertDatabaseHas('user_quotes', [
            'user_id' => $user->id,
            'quote_id' => $quote->id,
        ]);
    }

    public function test_it_logs_error_when_quote_is_not_found()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'inspire']);

        $this->quoteService->shouldReceive('getQuoteOfTheDay')
            ->once()
            ->with('inspire')
            ->andReturn(null);

        Log::shouldReceive('error')
            ->once()
            ->with('Error fetching quote for category \'inspire\'');

        $job = new UpdateDailyQuotes($this->quoteService);
        $job->handle();
    }
}
