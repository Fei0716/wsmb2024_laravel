<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index(){
        $albums = Album::where([
            'user_id' => Auth::user()->id,
        ])->get();
        return view('albums.index')->with(['albums'=> $albums]);
    }
    public function destroy(Album $album, Request $request){
        //delete all images in file system
        foreach($album->images as $i){
            if(Storage::disk('public')->exists($i->path)){
                Storage::disk('public')->delete($i->path);
            }
        }

        //delete album from database
        $album->delete();

        return redirect()->route('albums.index');
    }
    public function store(Request $request){
        $request->validate([
            'title'=> 'required',
            'status' => 'required|in:0,1',
        ]);

        $album = new Album();
        $album->title = $request->title;
        $album->status = $request->status;
        $album->user_id = Auth::user()->id;
        $album->save();

        return redirect()->route('albums.show');
    }
    public function show(Album $album){
      return view('albums.show')->with(['album'=> $album]);
    }
    public function update(Album $album  , Request $request){
        $request->validate([
            'title'=> 'required',
            'status' => 'required|in:0,1',
        ]);

        $album->title = $request->title;
        $album->status = $request->status;
        $album->save();

        return redirect()->route('albums.show' , $album);
    }
}
