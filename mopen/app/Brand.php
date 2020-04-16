<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        "name", "description", "website", "category_id"
    ];

    public function category()
    {
        return $this->hasOne(Category::class, "id", "category_id");
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, "brand_id", "id");
    }
}
