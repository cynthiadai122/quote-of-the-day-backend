<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Quote;
use GuzzleHttp\Client;

class QuoteService
{
    protected $client;

    protected $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = env('THEYSAIDSO_API_KEY');
    }

    public function getQuoteOfTheDay($category = 'inspire')
    {
        $response = $this->client->request('GET', 'https://quotes.rest/qod', [
            'query' => ['category' => $category],
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Theysaidso-Api-Secret' => $this->apiKey,
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $quoteData = $data['content']['quotes'][0] ?? null;
        if ($quoteData) {
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
            ]);

            return $quote;
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
