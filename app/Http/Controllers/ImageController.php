<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function index(Request $request){
        $images = Image::where([
            'album_id' => $request->album_id,
        ])->get();

        return response()->json(['images' => $images]);
    }
    public function store(Request $request){
        $request->validate([
            'images' => 'required',
        ]);
        //store images in file system
        $imageManager = new ImageManager(new Driver());

        foreach($request->file('images') as $image){
            $fileName = uniqid().'.'.$image->getClientOriginalName();
            //store first
            Storage::putFileAs('public/images' , $image , $fileName);

            $manager = new ImageManager(new Driver());
            $image = $manager->read('storage/images/'.$fileName);
            $height = $image->height();
            $width =  $image->width();
            if($height > 600 && $width > 600){
                // Resize the image to fit within the 600x600 boundary while maintaining aspect ratio
                $image->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            // Save the resized image back to the storage
            $image->save(storage_path('app/public/images/' . $fileName));

            //save in database
            $image = new Image();
            $image->path = 'images/'.$fileName;
            $image->album_id = $request->album_id;
            $image->save();

        }
        return response()->json(['message' => 'Images uploaded successfully']);

    }
}
