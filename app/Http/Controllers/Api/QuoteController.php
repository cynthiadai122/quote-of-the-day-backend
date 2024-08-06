<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\UserQuote;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

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
        $today = Carbon::now()->format('Y-m-d');
        $user = $request->user();
        $userQuote = UserQuote::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        if ($userQuote) {
            $quote = $userQuote->quote;
        } else {
            $favoriteCategories = $request->user()->favoriteCategories->pluck('name')->toArray();
            if (empty($favoriteCategories)) {
                $randomCategory = Category::inRandomOrder()->first();

                if ($randomCategory) {
                    $category = $randomCategory->name;
                } else {
                    $category = 'inspire';
                }
            } else {
                $category = $favoriteCategories[array_rand($favoriteCategories)];
            }

            $quote = $this->quoteService->getQuoteOfTheDay($category);
            $user->quotes()->attach($quote->id);
        }

        return response()->json($quote);
    }
}
