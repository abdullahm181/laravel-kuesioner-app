<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function index(){
        return view('Auth/login');
    }

    function login(Request $request){
        $request->validate(
            [
                'email'=>'required',
                'password' =>'required'
            ],[
                'email.required'=> 'Email wajib diisi',
                'password.required'=>'Password wajib diisi'
            ]
        );

        $infologin=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(Auth::attempt($infologin)){
            if(Auth::user()->role_id==1){
                return redirect('home');
            }elseif(Auth::user()->role_id==2){
                return redirect('home/admin');
            }
           
        }else{
            return redirect('')->withErrors('Username dan password yang dimasukkan tidak sesuai')->withInput();
        }
    }

    function logout(){
        Auth::logout();
        return redirect('');
    }
}
