<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Coupon;
use App\Http\Requests\CouponRequest;
use App\Jobs\CouponAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"])->except('show');
    }

    public function upload()
    {
        $brands = Brand::query()->with("category")->get();
        return view("coupons.upload", compact("brands"));
    }

    public function uploadFile(Request $request)
    {
        $file = $request->file('files');
        if (isset($file) && $file->getMimeType() == 'text/plain') {
            $destinationPath = 'uploads/' . time();
            $link = $destinationPath . '/' . $file->getClientOriginalName();
            $file->move($destinationPath, $file->getClientOriginalName());
            $contents = file(public_path($link));
            foreach ($contents as $item => $coupon) {
                $request->merge(['code' => $coupon, 'user_id' => auth()->user()->id]);
                $job = (new CouponAdd(json_encode($request->all())));
                dispatch($job)->delay(now()->addMinutes($item + 1));
            }
            unlink(public_path($link));
            return redirect()->back()->withErrors(['message' => 'لیست شما با موفقیت در صف اجرا قرار گرفت تا دقایق دیگری منتشر خواهر شذ']);
        } else {
            return redirect()->back()->withErrors(['message' => 'خطا در تجزیه بسته']);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $coupons = Coupon::query()->with("brand")->orderBy("created_at")->paginate(15);
        return $this->api ? response(["coupons" => $coupons]) : view("coupons.index", compact("coupons"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $brands = Brand::query()->with("category")->get();
        return view("coupons.create", compact("brands"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        isset($request->user_id) ? $request->user_id : $request->merge(['user_id' => auth()->user()->id]);
        $coupon = new Coupon([
            "name" => $request->name,
            "link" => $request->link,
            "code" => $request->code,
            "amount" => $request->amount,
            "brand_id" => $request->brand_id,
            "user_id" => $request->user_id,
            "type" => $request->type,
            "expired_at" => isset($request->expired_at) ? (Carbon::make($this->convertObServerToTimeStamp($request->expired_at))->endOfDay()) : null,
            "start_at" => isset($request->start_at) ? $this->convertObServerToTimeStamp($request->start_at) : now(),
            "publish_at" => isset($request->publish_at) ? $this->convertObServerToTimeStamp($request->publish_at) : now()
        ]);
        if ($this->api) {
            return $coupon->save() ? response(["message" => "با موفقیت درج شد"], "200") :
                response(["message" => "خطای رخ داد دوباره تلاش کنید"], 500);
        } else {
            return $coupon->save() ? redirect()->route("coupon.index")->withErrors(["message" => "با موفقیت درج شد"]) :
                redirect()->back()->withErrors(["message" => "خطای رخ داد دوباره تلاش کنید"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Coupon $coupon
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Coupon $coupon)
    {
        if ($coupon->status == 'show' || (auth()->check() && auth()->user()->id)) {
            $coupon = Coupon::query()->with("brand", 'like')->where("id", $coupon->id)->firstOrFail();
            return $this->api ? response(["coupon" => $coupon]) : view("coupons.show", compact("coupon"));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        $brands = Brand::query()->with("category")->get();
        return view("coupons.create", compact("brands", 'coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        if ($coupon->user_id == auth()->user()->id || auth()->user()->type == 'admin') {
            if (isset($request->status)) {
                $coupon->update([
                    "status" => $request->status,
                ]);
                return response(['message' => 'با موفقیت ویرایش شد']);
            } else {
                $coupon->update([
                    "name" => $request->name,
                    "link" => $request->link,
                    "code" => $request->code,
                    "amount" => $request->amount,
                    "brand_id" => $request->brand_id,
                    "user_id" => (int)$request->user()->id,
                    "type" => $request->type,
                    "expired_at" => isset($request->expired_at) ? $this->convertObServerToTimeStamp($request->expired_at) : null,
                    "start_at" => isset($request->start_at) ? $this->convertObServerToTimeStamp($request->start_at) : now(),
                    "publish_at" => isset($request->publish_at) ? $this->convertObServerToTimeStamp($request->publish_at) : now()
                ]);
                if ($this->api) {
                    return $coupon ? response(["message" => "با موفقیت درج شد"], "200") :
                        response(["message" => "خطای رخ داد دوباره تلاش کنید"], 500);
                } else {
                    return $coupon ? redirect()->route("coupon.index")->withErrors(["message" => "با موفقیت درج شد"]) :
                        redirect()->back()->withErrors(["message" => "خطای رخ داد دوباره تلاش کنید"]);
                }
            }
        } else {
            return response(['message' => 'شما دسترسی کافی برای انجام این عملیات را ندارید'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Coupon $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        if ($coupon->user_id == auth()->user()->id || auth()->user()->type == 'admin') {
            $delete = $coupon->delete();
            $delete ? response(['message' => 'با موفقیت ویرایش شذ'], 200) : response(['message' => 'خطای رخ داد دوباره تلاش کنید'], 500);
        } else {
            return response(['message' => 'شما دسترسی کافی برای انجام این عملیات را ندارید'], 403);
        }
    }
}
