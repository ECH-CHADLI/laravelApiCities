<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index($cityId) { // Logic to retrieve and return a list of comments for a specific city
        $citiesJson = file_get_contents(storage_path('app/cities.json'));
        $cities = json_decode($citiesJson, true);

        $city = collect($cities)->firstWhere('name', $cityId); //create a new collection instance(map, filter, sum, reduce...)
        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $comments = Comment::with('user', 'subcomments.user')
            ->where('city_id', $cityId)
            ->get(); //earger loading the user and subcomments user relationships (loading relationships with the model)

        return response()->json($comments);
    }

    public function getComment($commentId) { // Logic to retrieve and return a list of comments for a specific comment
        $comment = Comment::with('user', 'subcomments.user')->find($commentId);
        
        if(!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        return response()->json($comment);
    } 

    public function store(Request $request, $cityId) {
        $citiesJson = file_get_contents(storage_path('app/cities.json'));
        $cities = json_decode($citiesJson, true);

        //\Log::info('city id: ' . $cityId);
        //dd('city id: ' . $cities->id);

        $request->validate([
            'user_id' => 'required',
            'content' => 'required',
        ]);

        $city = collect($cities)->firstWhere('name', $cityId);
        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $comment = Comment::create([
            'user_id' => $request->user_id,
            'city_id' => $cityId,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
            //'likes' => $request->likes,
        ]);
        return response()->json($comment, 201);
    }
}
