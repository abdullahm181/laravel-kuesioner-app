<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    function index(){
        // echo "hallo, selamat data user";
        // echo "<h1>".Auth::user()->name." ~ ".Auth::user()->role_id."</h1>";
        // echo "<a href='/logout'>Logout >></a>";
        return view('home/home');
    }
    function admin(){
        echo "hallo, selamat datang admin";
        echo "<h1>".Auth::user()->name." ~ ".Auth::user()->role_id."</h1>";
        echo "<a href='/logout'>Logout >></a>";
    }
}
