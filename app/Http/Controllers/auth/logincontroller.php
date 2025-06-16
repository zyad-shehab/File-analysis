<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logincontroller extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function authenticate(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $email=$request->input('email');
        $password=$request->input('password');
        
        if(Auth::attempt(['email'=>$email,'password'=>$password])){
            $request->session()->regenerate();
            return redirect()->route('documents.list');
           
        }else {
        return  redirect()->back()->with('danger', 'Invalid email or password');
         
        }

      
    }
    public function logout(Request $request){
     auth::logout();
     $request->session()->invalidate();
     $request->session()->regenerate();
     return redirect()->route('login');
    }
}
