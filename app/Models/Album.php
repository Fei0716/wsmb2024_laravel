<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    public function images(){
        return $this->hasMany(Image::class , 'album_id' , 'id');
    }
    public function user(){
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
    public function likes(){
        return $this->hasManyThrough(Like::class, Image::class , 'album_id' , 'image_id');
    }
}
