<?php

namespace App\Http\Controllers\Api;

use App\Models\UserCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('getUserCategory', 'update');
    }

    public function getUserCategory(Request $request)
    {
        $user = $request->user();
        $categories = $user->favoriteCategories;

        return response()->json($categories->map(function ($category) {
            return $category->only(['id', 'name']);
        }));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $categoryIds = $request->input('categories', []);
        UserCategory::where('user_id', $user->id)->delete();

        foreach ($categoryIds as $categoryId) {
            UserCategory::create([
                'user_id' => $user->id,
                'category_id' => $categoryId,
            ]);
        }

        return response()->json([
            'message' => 'Preferences updated succussfully!',
        ], 200);
    }
}
