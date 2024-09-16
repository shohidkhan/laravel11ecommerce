<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    protected $guarded = [];

    function category() {
        return $this->belongsTo(Category::class);
    }

    function brand() {
        return $this->belongsTo(Brand::class);
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
