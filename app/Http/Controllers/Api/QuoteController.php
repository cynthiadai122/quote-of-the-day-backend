<?php

namespace App\Http\Controllers\Api;

use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
        $this->middleware('auth:sanctum')->only('quoteOfTheDay');
    }

    public function quoteOfTheDay(Request $request)
    {
        $favoriteCategories = $request->user()->favoriteCategories->pluck('name')->toArray();

        if (empty($favoriteCategories)) {
            $favoriteCategories = ['inspire'];
        }

        $category = $favoriteCategories[array_rand($favoriteCategories)];

        $quote = $this->quoteService->getQuoteOfTheDay($category);

        return response()->json($quote);
    }
}
