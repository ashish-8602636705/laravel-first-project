<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class LoginController extends Controller
{
    public function index(Request $req){
        $username = $req->input('username');
        $password = md5($req->input('password'));
        
        $data = User::where('email',$username)->where('password',$password)
                ->get()->toarray();
        if(isset($data[0])&&!empty($data[0])){
            
            $req->session()->put('id', $data[0]['id']);
            $req->session()->put('name', $data[0]['name']);
            $req->session()->put('email', $data[0]['email']);
            $req->session()->put('role', $data[0]['role']);
            $req->session()->put('phone', $data[0]['phone']);
            $req->session()->put('is_logged_in', 1);

            return redirect('dashboard');
             
        }else{
            
            return redirect('/')->with('error', 'Invalid login credentials');
        }
        //echo "<pre>";print_r($record);die;
    }
    
    public function logout(Request $req){
        
        $req->session()->flush();
        return redirect('/')->with('success');
    }
}
