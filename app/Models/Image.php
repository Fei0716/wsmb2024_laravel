<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public function likes(){
        return $this->hasMany(Like::class , 'image_id' ,'id');
    }


}
