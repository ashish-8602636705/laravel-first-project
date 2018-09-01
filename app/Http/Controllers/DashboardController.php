<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Brandcategories;
use App\Product;
use App\Result;
use DB;
class DashboardController extends Controller
{
    public function __construct(Request $req) {
        
         $this->middleware(function ($request, $next) 
        {
          $is_logged_in = $request->session()->get('is_logged_in');
          if($is_logged_in != 1)
            return redirect('/');

            return $next($request);

         });
    }
    
    public function index(Request $req){
            
            if ($req->session()->exists('is_logged_in')) {
                $is_logged_in = $req->session()->get('is_logged_in');
                
                if($is_logged_in=='1'){

                  $user['user_count']     = User::where('status',1)->where('id','<>',1)->count();
                  $user['brand_count']    = Brandcategories::where('status',1)->count();
                  $user['product_count']  = Product::where('status',1)->count();
                  $user['result_count']   = Result::where('status',1)->count();
                    return view('admin.dashboard',$user);
                }else{
                    
                    return redirect('/');
                }
            }else{
                 return redirect('/');
            }
    }
    
    public function getUserProfile(Request $request)
    {
          $id           = session()->get('id');
          $encid = encrypt($id);
          $query        = DB::table('users')->where('id',$id)->first();
          $data['data']  = array('name'    =>$query->name,
                                'password'=>$query->password,
                                'email'   =>$query->email,
                                'id'      =>$encid);
           return view('admin.profile',$data);
    }

    public function editpersonalinfo($id)
    {

        $id           = session()->get('id');
          $encid = encrypt($id);
          $query        = DB::table('users')->where('id',$id)->first();
          $data['data']  = array('name'    =>$query->name,
                                'password'=>$query->password,
                                'email'   =>$query->email,
                                'id'      =>$encid);
           return view('admin.editprofile',$data);
    }

    public function updateadmin(Request $request)
    {
        $id      =  session()->get('id');
        $encid    = encrypt(session()->get('id'));
        $data    =  array('name'    => $request->name,
                                'email'   => $request->email,
                                'password'=>md5($request->password));

              $validator = Validator::make($request->all(), [
                        'name'         => 'required',
                    'password' => 'required' ,
                    'email' => 'required' 
                    ]);

                       if ($validator->fails()) {
                       
                        return redirect('editinfo/'.$encid)
                                    ->withErrors($validator)
                                    ->withInput();
            }
        
            else
            {
                $update   =  DB::table('users')
                        ->where('id', $id)
                        ->update($data);
                       
        if ($update ==  TRUE) {
            return redirect('profile')->with('success', 'Profile updated successfully');
             // return redirect('profile')
             //            ->withErrors('profile updated successfully')
             //            ->withInput();
        }
        else
        {
           return redirect('editinfo/'.$id)
                                    ->withErrors('something went wrong')
                                    ->withInput();
        }
            }
           


    }
    
        
    
}
