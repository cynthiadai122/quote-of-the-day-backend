<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Quote;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class QuoteService
{
    protected $client;

    protected $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = env('THEYSAIDSO_API_KEY');
        Log::info('Loaded API Key: '.$this->apiKey);
    }

    public function getQuoteOfTheDay($category = 'inspire')
    {
        try {
            $response = $this->client->request('GET', 'https://quotes.rest/qod', [
                'query' => ['category' => $category],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Theysaidso-Api-Secret' => $this->apiKey,
                ],
            ]);

            $responseBody = $response->getBody()->getContents();

            Log::info('API Response Body: '.$responseBody);

            $data = json_decode($responseBody, true);

            Log::info('Decoded API Response: ', $data);

            $quoteData = $data['contents']['quotes'][0] ?? null;

            if ($quoteData) {
                $existingQuote = Quote::where('api_id', $quoteData['id'])->first();

                if ($existingQuote) {
                    return $existingQuote;
                }
                $quote = Quote::create([
                    'quote' => $quoteData['quote'],
                    'author' => $quoteData['author'],
                    'length' => $quoteData['length'],
                    'language' => $quoteData['language'],
                    'tags' => json_encode($quoteData['tags']),
                    'permalink' => $quoteData['permalink'],
                    'title' => $quoteData['title'],
                    'background' => $quoteData['background'],
                    'date' => $quoteData['date'],
                    'category_id' => $this->getCategoryId($quoteData['category']),
                    'api_id' => $quoteData['id'],
                ]);

                return $quote;
            }
        } catch (\Exception $e) {
            Log::error('Error fetching quote: '.$e->getMessage());
        }

        return null;
    }

    private function getCategoryId($categoryName)
    {
        $category = Category::firstOrCreate([
            'name' => $categoryName,
        ]);

        return $category->id;
    }
}
