<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('toggleFavorite', 'getFavorites');
    }

    public function toggleFavorite(Request $request)
    {
        $validatedData = $request->validate([
            'quote_id' => 'required|exists:quotes,id',
        ]);
        $user = $request->user();
        $quoteId = $validatedData['quote_id'];
        $favorite = Favorite::where(
            'user_id', $user->id
        )->where(
            'quote_id', $quoteId
        )->first();
        if ($favorite) {
            $favorite->delete();

            return response()->json(['message' => 'Favorite removed'], 200);
        } else {
            Favorite::create(
                [
                    'user_id' => $user->id,
                    'quote_id' => $quoteId,
                ]
            );

            return response()->json(['message' => 'Favorite added'], 200);
        }
    }

    public function getFavorites(Request $request)
    {
        $user = $request->user();

        $favorites = Favorite::where('user_id', $user->id)
            ->with('quote')
            ->get()
            ->map(function ($favorite) {
                return $favorite->quote;
            });

        return response()->json(['data' => $favorites]);
    }
}
