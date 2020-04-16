<?php

namespace App\Jobs;

use App\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CouponController;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;

class CouponAdd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request = null;

    /**
     * Create a new job instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = json_decode($request);
    }

    /**
     * Execute the job.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle()
    {
        $request = $this->request;
        $con = new Controller();
        $coupon = new Coupon([
            "name" => $request->name,
            "link" => $request->link,
            "code" => $request->code,
            "amount" => $request->amount,
            "brand_id" => $request->brand_id,
            "user_id" => $request->user_id,
            "type" => $request->type,
            "expired_at" => isset($request->expired_at) ? (Carbon::make($con->convertObServerToTimeStamp($request->expired_at))->endOfDay()) : null,
            "start_at" => isset($request->start_at) ? $con->convertObServerToTimeStamp($request->start_at) : now(),
            "publish_at" => isset($request->publish_at) ? $con->convertObServerToTimeStamp($request->publish_at) : now()
        ]);
        $coupon->save();
    }
}
