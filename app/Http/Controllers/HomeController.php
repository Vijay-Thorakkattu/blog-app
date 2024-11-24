<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

        // return view('home');
        if (Auth::check()) {
            $user = Auth::user();
            if($user->hasRole('admin') ){
                return redirect()->route('admin.home');
            }elseif($user->hasRole('vendor')){
                return redirect()->route('vendor.home');
            }elseif($user->hasRole('user')){
                return redirect()->route('user.home');
            }
        }
    }
}
