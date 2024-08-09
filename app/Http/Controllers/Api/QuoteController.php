<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Favorite;
use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
        $this->middleware('auth:sanctum')->only('quoteOfTheDay', 'missedQuotes', 'isFavorite');
    }

    public function quoteOfTheDay(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $user = $request->user();
        $userQuote = $user->quotes()
            ->whereDate('user_quotes.created_at', $today)
            ->first();

        if ($userQuote) {
            $quote = $userQuote;
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
            if (! $quote) {
                return response()->json([
                    'error' => 'No quote found for today. Please try again later.',
                ], 404);
            }

            $user->quotes()->attach($quote->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json($quote);
    }

    public function missedQuotes(Request $request)
    {
        $user = $request->user();
        Log::info($user);
        $previous_login = $user->previous_login;

        if (! $previous_login) {
            return response()->json([]);
        }

        $missedQuotes = $user->quotes()
            ->where('user_quotes.created_at', '>', $previous_login)
            ->get();

        return response()->json($missedQuotes);
    }

    public function isFavorite(Request $request, $id)
    {
        $user = $request->user();
        $isFavorite = Favorite::where('user_id', $user->id)
            ->where('quote_id', $id)
            ->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }
}
