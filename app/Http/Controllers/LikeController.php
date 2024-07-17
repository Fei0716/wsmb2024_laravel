<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request){
        $like = Like::where([
            'image_id' => $request->imageId,
            'user_id' => $request->userId,
        ])->get();

        //unlike
        if($like->count() > 0){
            $like->first()->delete();
            $album = Album::find($request->albumId);
            return response()->json(['message' => 'Like removed'  , 'data' => $album->likes]);
        }

        $album = Album::find($request->albumId);
//like
        $like = new Like();
        $like->image_id = $request->imageId;
        $like->user_id = $request->userId;
        $like->save();

        return response()->json(['message' => 'Like added' , 'data' => $album->likes]);

    }
}
