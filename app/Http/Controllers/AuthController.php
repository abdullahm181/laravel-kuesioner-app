<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function index(){
        return view('auth/login');
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
            // if(Auth::user()->role_id==1){
            //     return redirect('home');
            // }elseif(Auth::user()->role_id==2){
            //     return redirect('home/admin');
            // }
            return redirect('home');
        }
        return redirect('')->withErrors('Username dan password yang dimasukkan tidak sesuai')->withInput();
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
 
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
        ]);
 
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    function logout(){
        Auth::logout();
        return redirect('');
    }
}
