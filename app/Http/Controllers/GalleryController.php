<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class GalleryController extends Controller
{
    public function index(){
        $albums = Album::where([
            'status' => '0',//fetch only public albums
        ])->get();
//        $albums =  DB::select('SELECT * FROM albums WHERE status = "0"');
        return view('gallery.index')->with(['albums'=> $albums]);
    }
    public function show(Album $album){
        return view('gallery.show')->with(['album' => $album]);
    }
}
