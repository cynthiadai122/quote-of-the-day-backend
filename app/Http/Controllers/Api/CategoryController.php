<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCategory;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('update');
    }

    public function index(){
        $categories = Category::all();
        return response()->json($categories);
    }

    public function update(Request $request){
        $user = Auth::user();
        $categoryIds = $request->input('categories',[]);
        UserCategory::where('user_id',$user->id)->delete();

        foreach($categoryIds as $categoryId){
            UserCategory::create([
                'user_id' => $user->id,
                'category_id'=> $categoryId
            ]);
        }
        return response()->json([
            'message'=> 'Preferences updated succussfully!'
        ],200);
    }
}
