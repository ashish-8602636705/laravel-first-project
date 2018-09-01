<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test;
use App\Assay;
use App\Text;
use DB;
use App\TestColor;
use App\Image;


class ChartController extends Controller
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
    public function index($id)
    {
    	$data['data'] = DB::table("test_color_value")
	               ->select ("test_color_value.*", "product.product_name","assay.assay_short","assay.assay_long")
	               ->leftjoin("product", "test_color_value.product_id", "=", "product.id")
	               ->leftjoin("assay", "test_color_value.assay_id", "=", "assay.id")
	               ->where('test_color_value.product_id',$id)
	               ->where('test_color_value.status',1)
	               ->get()->toarray();
       $data['image']  = Image::where('status',1)->select('id','img','img_description')->get()->toArray();
       $data['text']   = Text::where('status',1)->select('id','text')->get()->toArray();
	   $data['assay_data'] = Assay::get()->where('status',1)->toArray();
       $data['product_id']   = $id;
    	return view('admin.testchart',$data);
    }
    public function gettestcolor($id,$product_id)
    {
    	$data['data']         = Test::where('status',1)->where('id',$id)->get(array('test_name'))->toArray();
    	$colorjson            = TestColor::where('status',1)->where('test_id',$id)
    								->where('product_id',$product_id)
    								->get()->toArray();

    	if(!empty($colorjson))
    	{
	    	$color                = $colorjson[0]['color_code'];
	    	$value                = $colorjson[0]['value'];
	    	$data['color']        = json_decode($color);
	    	$data['value']        = json_decode($value);
    	}
    	
    	$data['product_id']   = $product_id;
    	$data['id']   = $id;
    	return view('admin.testcolor',$data);
    }

    public function savecolorandvalue(Request $request)
    {
    	$test_id        = $request->test_id;
    	$product_id     = $request->product_id;
    	$c1             = $request->c1;
    	$c2             = $request->c2;
    	$c3   			= $request->c3;
    	$c4   			= $request->c4;
    	$c5  			= $request->c5;
    	$c6   			= $request->c6;
    	$c7   			= $request->c7;
    	$v1   			= $request->v1;
    	$v2   			= $request->v2;
    	$v3   			= $request->v3;
    	$v4   			= $request->v4;
    	$v5   			= $request->v5;
    	$v6   			= $request->v6;
    	$v7   			= $request->v7;

    	$colorArray     = array('c1'=>$c1,
			    				 'c2'=>$c2,
			    				 'c3'=>$c3,
			    				 'c4'=>$c4,
			    				 'c5'=>$c5,
			    				 'c6'=>$c6,
			    				 'c7'=>$c7
			    				);
    	$valueArray     = array('v1'=>$v1,
			    				 'v2'=>$v2,
			    				 'v3'=>$v3,
			    				 'v4'=>$v4,
			    				 'v5'=>$v5,
			    				 'v6'=>$v6,
			    				 'v7'=>$v7
			    				);
    	$colorjson      = json_encode($colorArray,true);
    	$valuejson      = json_encode($valueArray,true);

    	$testsave             = new TestColor;
    	$testsave->test_id    = $test_id;
    	$testsave->product_id = $product_id;
    	$testsave->color_code = $colorjson;
    	$testsave->value      = $valuejson;

    	$save                 = $testsave->save();

    	if($save)
    	{
    		return redirect()->back()->with('success', 'Color And Value Added Successfully!');
    	}

    }

    public function addtestnameandcolorandvalue($id)
    {
    	$data['product_id']  = $id;
    	$data['data']        = Assay::get()->where('status',1)->toArray();
    	return view('admin.add_test',$data);
    }
    public function saveaddedtest(Request $request)
    {   

    		$product_id                                = $request->product_id;
    		$assay_id                                  = $request->assay_id;
            $getUnitName  = Assay::where([['status',1],['id',$assay_id]])->select('unit')->get()->toArray();
            $unit         = $getUnitName[0]['unit']; 
    		$colorandvaluesave                         = new TestColor();
        	$colorandvaluesave->product_id             = $product_id;
        	$colorandvaluesave->assay_id               = $assay_id;
          	$colorandvaluesave->product_id             = $product_id;
          	$colorandvaluesave->color_code_and_value   = '[
									    					{"c1":"#fefefe",
									    					 "v1":"-",
                                                             "tc1":"#0f0f0f",
                                                             "unit1":"'.$unit.'",
                                                             "description":"",
                                                             "img":""
									    					},
									    					{"c2":"#fefefe",
									    					 "v2":"-",
                                                             "tc2":"#0f0f0f",
                                                             "unit2":"'.$unit.'",
                                                             "description":"",
                                                             "img":""
									    					},
									    					{"c3":"#fefefe",
									    					 "v3":"-",
                                                             "tc3":"#0f0f0f",
                                                             "unit3":"'.$unit.'",
                                                             "description":"",
                                                             "img":""

									    					},
									    					{"c4":"#fefefe",
									    					 "v4":"-",
                                                             "tc4":"#0f0f0f",
                                                             "unit4":"'.$unit.'",
                                                             "description":"",
                                                             "img":""
									    					},
									    					{"c5":"#fefefe",
									    					 "v5":"-",
                                                             "tc5":"#0f0f0f",
                                                             "unit5":"'.$unit.'",
                                                             "description":"",
                                                             "img":""
									    					},
									    					{"c6":"#fefefe",
									    					 "v6":"-",
                                                             "tc6":"#0f0f0f",
                                                             "unit6":"'.$unit.'",
                                                             "description":"",
                                                             "img":""
									    					},
									    					{"c7":"#fefefe",
									    					 "v7":"-",
                                                             "tc7":"#0f0f0f",
                                                             "unit7":"'.$unit.'",
                                                             "description":"",
                                                             "img":""
									    					}
									    					
									    				]';

          	$save                            = $colorandvaluesave->save();

          	if(isset($save))
          
          		return redirect('view/'.$product_id)->with('success', 'Test Name Updated Successfully');
       
    }

        public function updatechartbyjquery(Request $request)
        {
        	$color_data   = $request->color_data;
        	$encoded_color= json_encode($color_data);
        	$product_id   = $request->product_id;
        	$assay_id     = $request->assay_id;
        	$row_id       = $request->row_id;
        	
        	$colorandvaluesave                         = TestColor::find($row_id);
        	$colorandvaluesave->product_id             = $product_id;
        	$colorandvaluesave->assay_id               = $assay_id;
        	$colorandvaluesave->color_code_and_value   = $encoded_color;

	        	$updatedata                      = $colorandvaluesave->save();

	        	if (isset($updatedata)) 
	        	{
	        		return redirect('view/'.$product_id)->with('success', 'Test Color And Value Updated Successfully');
	        	}
        	
        	
        }

        public function deletechart($product_id,$id)
        {
        	$update_id                  = $id;
        	$colorandvaluesave          = TestColor::find($update_id);
        	$colorandvaluesave->status  = 0;
        	$updatedata                 = $colorandvaluesave->save();
        	if (isset($updatedata)) 
        	{
        		return redirect('view/'.$product_id)->with('success', 'Row Deleted Successfully');
        	}
        }
    
}
