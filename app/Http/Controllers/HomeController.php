<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function like(Request $request)
    {
        $like = Like::query()->firstOrNew([
            "model_type" => $request->model_type,
            'model_id' => $request->model_id,
            "user_id" => auth()->user()->id,
            "type" => $request->type
        ]);
        return $like ? response(['message' => "با موفقیت ثبت شد"], 200) : response(['message' => 'حطای رخ داد'], 500);
    }
}
