<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Assay;
use DB;
use \Crypt;
use Excel;
use Illuminate\Support\Facades\Validator;
//use Maatwebsite\Excel\Concerns\FromCollection;
class ResetpasswordController extends Controller
{

   public function reset_password($email)
    {

        $decrypted = Crypt::decryptString($email);
        $data['email'] = $decrypted;
        return view('admin.password_reset',$data);
    }
    public function password_reset_save(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'password'=>'required|min:6'
                    ]);

        if($validator->fails())
        {
            
           return redirect()->back()->with('success', 'Password Must Be Atleast 6 Character');
            
        }

        elseif($request->password == $request->confirm_password )
        {
          $update = DB::table('users')
                 ->where('email', $request->email)
                 ->update(['password' => md5($request->password)]);

            if(isset($update))
             return redirect()->back()->with('success', 'Password Reset Successfully !');
            else
            return redirect()->back()->with('success', 'Something Went Wrog!');

        }
        else
        {
            return redirect()->back()->with('success', 'Password And Confirm password Must Match');
        } 

    }
}
