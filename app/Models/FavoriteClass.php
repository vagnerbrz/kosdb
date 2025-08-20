<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteClass extends Model
{
    
    protected $fillable = ['user_id', 'class_code'];
}
