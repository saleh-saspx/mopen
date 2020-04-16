<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Coupon;
use Faker\Generator as Faker;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "link" => $faker->linuxProcessor,
        "code" => "test-code-code" . rand(1, 99999999),
        "amount" => rand(1000, 10000000),
        "brand_id" => rand(1, 5),
        "type" => rand(0, 1) ? "coupon" : "offer",
        "expired_at" => now()->addYear(),
        "start_at" => now(),
        "publish_at" => now()
    ];
});
