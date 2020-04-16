<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("link");
            $table->string("code");
            $table->integer("amount");
            $table->integer("user_id");
            $table->integer("brand_id");
            $table->enum("type", ["coupon", "offer"])->default("coupon");
            $table->enum("status", ["show", "hold"])->default("hold");
            $table->timestamp("expired_at")->nullable();
            $table->timestamp("start_at")->nullable();
            $table->timestamp("publish_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
