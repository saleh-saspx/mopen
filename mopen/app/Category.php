<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        "name"
    ];

    public function Brands()
    {
        return $this->hasMany(Brand::class, "category_id", "id")->with("coupons");
    }
}
