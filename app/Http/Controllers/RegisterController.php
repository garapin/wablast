<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class RegisterController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store(Request $request){
       
        $request->validate([
            'username' => 'unique:users|min:4|required',
            'email' => 'unique:users|email|required',
            'password'  => 'required|min:6'
        ]);

    
        User::create(
            [
                'username' =>$request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'api_key' =>  Str::random(30),
                'chunk_blast' => 0

            ]
        );

        return redirect(route('login'))->with('alert',[
            'type' => 'success',
            'msg' => 'Registrasi success,please sign in'
        ]);
    }
}
