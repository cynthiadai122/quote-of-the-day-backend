<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Services\QuoteService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class QuoteServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $quoteService;

    protected $clientMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientMock = Mockery::mock(Client::class);
        $this->app->instance(Client::class, $this->clientMock);
        $this->quoteService = new QuoteService($this->clientMock);
    }

    public function test_get_quote_of_the_day_saves_to_database_and_returns_quote()
    {
        $mockResponse = new Response(
            200,
            [],
            json_encode([
                'contents' => [
                    'quotes' => [
                        [
                            'quote' => 'Sample quote',
                            'author' => 'Sample author',
                            'length' => 123,
                            'language' => 'en',
                            'tags' => ['tag1', 'tag2'],
                            'permalink' => 'http://example.com',
                            'title' => 'Sample title',
                            'background' => 'http://example.com/image.jpg',
                            'date' => '2024-08-04',
                            'category' => 'inspire',
                        ],
                    ],
                ],
            ])
        );

        $this->clientMock->shouldReceive('request')
            ->once()
            ->with('GET', 'https://quotes.rest/qod', [
                'query' => ['category' => 'inspire'],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Theysaidso-Api-Secret' => env('THEYSAIDSO_API_KEY'),
                ],
            ])
            ->andReturn($mockResponse);

        $category = Category::factory()->create([
            'name' => 'inspire',
        ]);

        $quote = $this->quoteService->getQuoteOfTheDay('inspire');

        $this->assertNotNull($quote);
        $this->assertEquals('Sample quote', $quote->quote);
        $this->assertDatabaseHas('quotes', [
            'quote' => 'Sample quote',
            'author' => 'Sample author',
            'length' => 123,
            'language' => 'en',
            'tags' => json_encode(['tag1', 'tag2']),
            'permalink' => 'http://example.com',
            'title' => 'Sample title',
            'background' => 'http://example.com/image.jpg',
            'date' => '2024-08-04',
            'category_id' => $category->id,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
