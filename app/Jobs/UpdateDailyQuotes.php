<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\User;
use App\Services\QuoteService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateDailyQuotes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function handle()
    {
        $users = User::all();
        $today = Carbon::now()->format('Y-m-d');

        foreach ($users as $user) {
            $userQuote = $user->quotes()
                ->whereDate('user_quotes.created_at', $today)
                ->first();

            if ($userQuote) {
                continue;
            }

            $favoriteCategories = $user->favoriteCategories->pluck('name')->toArray();
            if (empty($favoriteCategories)) {
                $randomCategory = Category::inRandomOrder()->first();
                $category = $randomCategory ? $randomCategory->name : 'inspire';
            } else {
                $category = $favoriteCategories[array_rand($favoriteCategories)];
            }

            $quote = $this->quoteService->getQuoteOfTheDay($category);
            if ($quote) {
                $user->quotes()->attach($quote->id);
            } else {
                Log::error("Error fetching quote for category '{$category}'");
            }
        }
    }
}
