<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        "name", "code", "amount", "user_id", "link", "brand_id", "type", "expired_at", "start_at", "publish_at", "status"
    ];

    public function brand()
    {
        // Join To Brand Model For Get Brand Detail And BrandCategory
        return $this->hasOne(Brand::class, "id", "brand_id")->with("category");
    }

    public function user()
    {
        // Join To Brand Model For Get Brand Detail And BrandCategory
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function getStatusTextAttribute()
    {
        switch ($this->attributes["status"]) {
            case "show":
                return $this->attributes["expired_at"] != null && $this->attributes["expired_at"] < now() ? ["class" => 'danger', "text" => 'تایید شده- منقضی شده'] : ["class" => 'success', "text" => 'تایید شده'];
                break;
            case "hold":
                return $this->attributes["expired_at"] != null && $this->attributes["expired_at"] < now() ? ["class" => 'danger', "text" => 'در انتظار تایید- منقضی شده'] : ["class" => 'danger', "text" => 'در انتظار تایید'];
                break;
        }
    }

    public function getEditUrlAttribute()
    {
        return route('coupon.edit', ['coupon' => $this->id]);
    }

    public function getShowUrlAttribute()
    {
        return route('coupon.show', ['coupon' => $this->id]);
    }

    public function getUpdateUrlAttribute()
    {
        return route('coupon.update', ['coupon' => $this->id]);
    }

    public function Like()
    {
        return $this->morphMany(Like::class, 'model');
    }
}
