<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Assay;
use DB;
use Excel;
use App\TermsAndConditions;
//use Maatwebsite\Excel\Concerns\FromCollection;
class TermsAndConditionsController extends APIController
{
    public function index(Request $request)
    {
    	$data['input'] = TermsAndConditions::where([['status',1],['id',1]])->select('input_data')->get()->toArray();
    	 
        return view('admin.termsandcondition',$data);
    }

    public function loadTermsAndConditionsPage(Request $request)
    {
        $is_logged_in = $request->session()->get('is_logged_in');
        if($is_logged_in != 1)
            return redirect('/');
        
    	$data['input'] = TermsAndConditions::where([['status',1],['id',1]])->select('input_data')->get()->toArray();
    	 
    	 return view('admin.t&c',$data);
    }
    public function saveTermsAndConditions(Request $request)
    {
    	 $terms = $request->terms;
    	 $save  = TermsAndConditions::find(1);
    	 $save->input_data = $terms;
    	 $save_Data  = $save->save();
    	 if ($save_Data) 
    	 {
    	 	return redirect('/t&c')->with('success', 'Terms And Conditons Added Successfully !');
    	 }
    }
     
     
    	
}
