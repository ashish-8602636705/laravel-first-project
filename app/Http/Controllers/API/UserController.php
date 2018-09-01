<?php

namespace App\Http\Controllers\API;
ini_set('memory_limit','-1');
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\APIController;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Brandcategories;
use App\Product;
use App\Illness;
use App\TestColor;
use App\Result;
use App\Assay;
use App\Text;
use App\Friend;
use App\Share;
use Mail;
use \Crypt;

class UserController extends APIController
{
    public function signup(Request $request)
    {
    	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'name' =>'required',
		            'password'=>'required',
		            'email'=>'required',
        			]);

        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        if(!empty($request->email))
        {
        	$user             = User::where('status',1)->where('email',$request->email)->get()->toArray();

        	if(!empty($user))
        	{

        		return  parent::api_response([], false, 'Email Allready Exist', 200);
        	}
      
        else
	        {
	        	$user                      = new User;
	        	$user->name                = $request->name;
	        	$user->email               = $request->email;
                $user->gcm_token           = $request->gcm_token;
	        	$user->password            = md5($request->password);
	        	
	        	
	        	
	        	if($request->age != '' )
	        	{
	        		$user->age             = $request->age;
                    $user->dob             = $request->dob;
	        	}
	        	else
	        	{
	        		$user->age             = NULL;
	        	}	

	        	if($request->age != '' )
	        	{
	        		$user->country         = $request->country;
	        	}
	        	else
	        	{
	        		$user->country         = NULL;
	        	}



        	if(!empty($request->diagnosed_illness))
        	{ 
	        		if(is_numeric($request->diagnosed_illness)== 1)
	        	{
                    
	        		$user->diagnosed_illness   = $request->diagnosed_illness;
	        		$saveuser         = $user->save();
	        	}
	        	else
	        	{

	        		$illness          = new Illness();
	        		$illness->name    = trim($request->diagnosed_illness);
	        		$illness->status  = 2;
	        	    $illness->save();
	        		$lastinserted_id  = $illness->id;

	        		if(!empty($lastinserted_id))
	        		{
		        		$user->diagnosed_illness   = $lastinserted_id;
		        		$saveuser                  = $user->save();
	        		}
	        		
	        	}
	        }
        	else
        	{
        		$user->diagnosed_illness   = NULL;
        		$saveuser         = $user->save();
        	}
	        		

	        		
	        	
	        	 if (isset($saveuser)) 
	        	 {
                    
	        	 	return  parent::api_response($user,true,'User Registered Successfully .', 200);
	        	 	
	        	 }
	        }
        }
      
    }

    public function signin(Request $request)
    {
    	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'password'=>'required',
		            'email'=>'required',
        			]);

        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        
        else
        {
           
            
        	$count         = User::where([['status',1],['email','=',$request->email],['password','=',md5($request->password)]])->select('id','name','email')->get()->toArray();

        	
        	if(!empty($count))
        	{
                 $userTokenUpdate = User::where([['email',$request->email],['status',1]])->select('id')->get()->toArray();
                 $userToken = User::find($userTokenUpdate[0]['id']);
                 $userToken->gcm_token = $request->gcm_token;
                 $userToken->save();
        		return  parent::api_response($count[0], true, 'Sign In Successfully', 200);
        	}
        	else
        	{
        		return  parent::api_response([], false, 'Email Or Password Incorrect', 200);
        	}	
        
        }
    }

     public function getAllBrand()
     {
     	$getbrand    =  Brandcategories::where('status',1)->select('id','brand_name')->get()->toArray();
     	
     	return  parent::api_response($getbrand, true, 'Brand List', 200);

     } 

     public function getAllProduct(Request $request)
     {
     	
     	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'brand_id'=>'required'
        			]);
        
        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
        {

            $getProduct = Product::where([['status',1],['brand_id',$request->brand_id]])->select('id','product_name')->get()->toArray();
            if(isset($getProduct) && !empty($getProduct))
            return  parent::api_response($getProduct, true, 'Prouct List', 200);
        	else
        	return  parent::api_response($getProduct, false, 'No Data Found', 200);

        }
     	
     }

     public function diagnosed_illness()
     {
     	$illness = Illness::where('status',1)->select('id','name')->get()->toArray();
     	if(isset($illness) && !empty($illness))
     	return  parent::api_response($illness, true, 'All Diagnosed Illness', 200);
     	else
     	return  parent::api_response([], false, 'No Data Found', 200);
     }

     public function getChart(Request $request)
     {
     	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'product_id'=>'required'
        			]);
        
        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
        {
        	
			 $get_chart    = TestColor::
						    join('assay', 'test_color_value.assay_id', '=', 'assay.id')
						    ->join('product', 'test_color_value.product_id', '=', 'product.id')
						    ->select('test_color_value.assay_id','test_color_value.product_id','test_color_value.color_code_and_value','assay.assay_short as assay_name', 'product.product_name')
						    ->where([['test_color_value.product_id',$request->product_id],['test_color_value.status',1]])
						    ->get()
						    ->toArray();
				
			foreach ($get_chart as $chartkey => $colorvalue) 
			{
				$getobject = $colorvalue['color_code_and_value'];
				$getArray  = json_decode($getobject,true);

				$get_chart[$chartkey]['color_code_and_value'] = $getArray;

			}
			

			if (isset($get_chart) && !empty($get_chart)) 
			{
				return  parent::api_response($get_chart, true, 'Show Chart', 200);	   	
			}	
			else
			{
				return  parent::api_response([], false, 'No Data Found', 200);
			}	   
        }	
     }

     public function updateUserProfile(Request $request)
     {
     	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'user_id'=>'required',
		            'name'=>'required',
		            'age'=>'required'
        			]);
        
        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
        {
            if($request->diagnosed_illness != '')
            {
                if(is_numeric($request->diagnosed_illness)== 1)
                {
                    $illness_id    = $request->diagnosed_illness;
                }
                else
                {
                    $illness   = new Illness();
                    $illness->name = $request->diagnosed_illness;
                    $illness->save();
                    $illness_id  = $illness->id;
                }
            }
            else
            {
                $getIllness = User::where([['status',1],['id',$request->user_id]])->select('diagnosed_illness')->get()->toArray();
                $illness_id = $getIllness[0]['diagnosed_illness'];
            }
            if($request->country != '')
            $country = $request->country;
            else
            $country = NULL;
            	$user             = User::find($request->user_id);
            	$user->name       = $request->name;
                $user->age           = $request->age;
                $user->country           = $country;
                $user->diagnosed_illness = $illness_id;
                $user->dob = $request->dob;
            	$update           = $user->save();
            	if (isset($update)) 
            	{
            		return  parent::api_response([], true, 'User Profile Updated Successfully', 200);
            	}

        }
     }

     public function forget_password(Request $request)
     {
     	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'email'=>'required'
        			]);
        $usermail   = $request->email;
        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
        {
        	$count = User::where([['email',$request->email],['status',1]])->count();
        	if ($count == '1') 
        	{
        		$data['title']      = "Reset Pasword For Puri";
        		$data['user_email'] = Crypt::encryptString($request->email);
				  	$mail =  Mail::send('admin.emailsend', $data, function($message) use ($usermail) {
				        $message->to($usermail, 'Receiver Name')->subject('Health App');
				    });

		    		return  parent::api_response([], true, 'Reset password link sent successfully on your email address.', 200);
		    	
		        		
		    }
        	
        	else
        	{
        		return  parent::api_response([], false, 'Email Does Not Exist', 200);
        	}	
        }
     }


     public function validate_version(Request $request)
     {
     	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'version'=>'required'
        			]);
        
        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        if($request->version == '1.1')
        {
        	return  parent::api_response([], true, 'Version validate successfully', 200);
        }	
        elseif($request->version == '1.0')
        {
        	return  parent::api_response([], 'warning', 'Update available for app.', 200);
        }
        else
        {
        	return  parent::api_response([], false, 'App version you are using is outdated please update app.', 200);
        }	
     } 

     public function socialLogin(Request $request)
     {
     	$input=$request->all(); 
        $validator=Validator::make($input,[
		            'version'=>'required'
        			]);
        
        if($validator->fails())
        {
        	
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
		else{

			$userData = DB::table('users')
			           ->select('id','social_id')
			           ->where('social_id',$request->social_id)
			           ->get();

			if(count($userData) > 0){
			$userData = $userData->toArray();
			$user_id = $userData[0]->id;
			return  parent::api_response($user_id, true, 'Already registered!', 200);
			}
			else
			{
				 $userdata            = new User;
				 $userdata->name      = $request->name;
				 $userdata->email     = $request->email;
				 $userdata->social_id = $request->social_id;
				 $userdata->save();
				 $id                  = $userdata->id;

				 if (isset($id) && !empty($id)) 
				    return  parent::api_response($id, true, 'User registered successfully!', 200);
				 
				 else
				 	return  parent::api_response([], false, 'User not registered successfully!', 200);
			}
			}

     }

     public function saveAndUpdateUserTest(Request $request)
     {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'assayData'=>'required',
                    'product_id'=>'required',
                    'user_id'=>'required',
                    'date'=>'required'
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

           $isProductExist = Product::join('test_color_value','product.id','=','test_color_value.product_id')
                             ->where([['product.status',1],['product.id',$request->product_id]])->count();

            $isUserExist   = User::where([['status',1],['id',$request->user_id]])->count();
            if($isProductExist > 0 && $isUserExist == 1)
            {

            
             $assayData  = $request->assayData;
             $json_decode = json_decode($assayData,true);
             $assay_id  = array_column($json_decode, 'assay_id');
             $assaynamesave = Assay::whereIn('id',$assay_id)->select('id','assay_short')->get()->toArray();

               if(isset($json_decode))
               {
                    $UnitDescAndImg = TestColor::whereIn('assay_id',$assay_id)->select('id','assay_id','product_id','description','img')->where([['status',1],['product_id',$request->product_id]])->get()->toArray();
                    
                    $data = array();
                    $decsData = array();
                    $descimg  = array();
                    
                    $datadecode = array();
                  

                    foreach ($assaynamesave as $key => $value) 
                    {
                        $json_decode[$key]['assay_name'] = $value['assay_short'];
                    }
                        $encodeData = json_encode($json_decode);

                   if($request->result_id != '' && $request->image == '')
                   {
                      $getPreviousImage = Result::where([['status',1],['id',$request->result_id]])->select('image')->get()->toArray();
                      $getimage = $getPreviousImage[0]['image'];
                      if($getimage != '')
                      { 
                        $imageName = $getimage;
                        
                      }
                      else
                       {
                         $imageName  = NULL;
                       } 
                      
                   }
                   elseif($request->image != '')
                   {    
                      $imageName = time().'.'.$request->image->getClientOriginalExtension();
                      $request->image->move(public_path('images'), $imageName);

                   }
                   else
                   {
                     $imageName  = NULL;
                   }

                   
                   
                   if($request->result_id != '')
                   {
                      $result       = Result::find($request->result_id);
                   }
                    
                    else
                    {
                        $result       = new Result();
                    }

                    $var = $request->date;
                    $date = str_replace('.', '-', $var);
                    $date_formate =  date('Y-m-d', strtotime($date));  
                   // $year = date('y');
                    $newdate = $request->date;
                    $year =  date("y",strtotime($newdate)); 
                   $result->user_id = $request->user_id;
                   $result->product_id = $request->product_id;
                   $result->color_and_value = $encodeData;
                   $result->date = $date_formate;
                   $result->image = $imageName;
                   $result->years = $year;
                   $saveresult    =  $result->save();
                   $id            = $result->id;
                   if($saveresult)
                   {
                      
                        $checkAllSharedstatus = Friend::where([['user_id',$request->user_id],['share_all',1],['is_friend',1]])->select('friend_id')->get()->toArray();
                           if(!empty($checkAllSharedstatus))
                           {
                                foreach ($checkAllSharedstatus as $key => $value) 
                                {
                                  $insertInshareTable = new Share();
                                  $insertInshareTable->user_id = $request->user_id;
                                  $insertInshareTable->result_id= $id;
                                  $insertInshareTable->friends_id= $value['friend_id'];
                                  $insertInshareTable->save();
                                }
                           }
                    return  parent::api_response(array('id'=>$id), true, 'Test save successfully', 200);

                   }
                   else
                   {
                    return  parent::api_response([], false, 'Test not save ', 200); 
                   }

               }
              else
               {
                 return  parent::api_response([], false, 'Something went wrong', 200);
               }    
        }
        else
        {
           return  parent::api_response([], false, 'Invalid product or user id', 200);
        }  
     }

     public function showResult(Request $request)
     {
         $input=$request->all(); 
        $validator=Validator::make($input,[
                    'id'=>'required',
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
            $isExistid = Result::find($request->id);

            if($isExistid != '')
            {
               $result   = Result::join('product','result.product_id','=','product.id')
                            ->join('brand_categories','product.brand_id','=','brand_categories.id')
                            ->select('product.product_name','result.*','brand_categories.brand_name')
                            ->where([['result.status',1],['result.id',$request->id]])->get()->toArray();

             $colordata = $result[0]['color_and_value'] ;
             $jsondec = json_decode($colordata,true);
             $columnData = array_column($jsondec, 'assay_id');

             $assay = Assay::whereIn('id',$columnData)->select('id','assay_short','unit')->get()->toArray();
             $getobject = $result[0]['color_and_value'];
             $jsondec = json_decode($getobject,true);
                foreach ($assay as $key => $value)
                {
                    $jsondec[$key]['assay_name'] = $value['assay_short'];
                    $jsondec[$key]['unit'] = $value['unit'];

                }
                 $colorData = $result[0]['color_and_value'];
                 $color_and_value = json_decode($colorData,true);
                 unset($result[0]['color_and_value']);
                    // foreach ($jsondec as $key1 => $value1) 
                    // {
                    //      $newdata = $value1['description'];
                    //      $getDescription = Text::where([['status',1],['id',$newdata]])->select('text')->first();
                    //      $jsondec[$key1]['description'] = $getDescription->text;
                          
                    // }
                if($result[0]['image'] != NULL)
                $getImageUrl = url('images').'/'.$result[0]['image']; 
                else
                $getImageUrl = NULL;    
                // $result[$chartkey]['color_and_value'] = $getobject;
                // $result[$chartkey]['image'] = $getImageUrl;
               // $result['data'] = $jsondec;
                 //$img  = $getImageUrl;
                 $result1    = $result[0];
                 $result1['img_url'] = $getImageUrl;
                
               if(!empty($result))
                {
                    $payload=[
                'status' => 'true',
                'message' => 'Test Result',
                'data'=>$result1,
                'colorAndValue'=>$jsondec
             
             ];
             echo json_encode($payload);
                }
               else
                return  parent::api_response([], false, 'No data found', 200);
            }
            else
            {
                return  parent::api_response([], false, 'invalid id', 200);
            } 

     }

     public function getProfile(Request $request)
     {
          $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id'=>'required',
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $userIllness = User::where([['status',1],['id',$request->user_id]])->select('diagnosed_illness')->get()->toArray();
        if(!empty($userIllness[0]['diagnosed_illness']))
        {
            $user  = User::join('diagnosed_illness','users.diagnosed_illness','=','diagnosed_illness.id')
                    ->where([['users.status',1],['users.id',$request->user_id]])->select('users.id','users.name','users.email','diagnosed_illness.name as diagnosed_illness','users.age','users.country','users.dob')->get()->toArray();
        }
        else
        {
            $user = User::where([['status',1],['id',$request->user_id]])->select('id','name','email','diagnosed_illness','age','country','dob')->get()->toArray();
        } 

        

        $illnessArray = Illness::where('status',1)->select('id','name')->get();
        if(!empty($user))
        {
            //array_push($user, $illnessArray);
            $array = array(
                            "status"=>true,
                            "message"=>'User Profile',
                            "data"=>$user[0],
                            "All_illnesss"=>$illnessArray,
                          );
            echo json_encode($array);
           // return  parent::api_response($user, true, 'User Profile', 200);
        }
        
        else
        {
            return  parent::api_response([], false, 'Invalid User_id', 200); 
        }
         

        

     }

     public function changeEmail(Request $request)
     {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'old_email'=>'required',
                    'new_email'=>'required',
                    'user_id'=>'required',
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $user  = User::where([['status',1],['email',$request->old_email],['id',$request->user_id]])->select('email')->get()->toArray();
        if(!empty($user))
        {
            $checkAllreadyExistNewEmail  = User::where([['status',1],['email',$request->new_email]])->get()->toArray();
            if(empty($checkAllreadyExistNewEmail))
            {
                $updateEmail  = User::find($request->user_id);
                $updateEmail->email = $request->new_email;
                $update       =  $updateEmail->save();
                if (isset($update)) 
                {
                    return  parent::api_response([], true, 'Email updated successfully', 200);
                }
                else
                {
                    return  parent::api_response([], false, 'Something went wrong', 200);
                }  

            } 
            else
            {
                return  parent::api_response([], false, 'new email allready exist', 200);
            }   
        }
        else
        {
            return  parent::api_response([], false, 'Invalid user id or old email', 200);
        } 
     }

     public function changePassword(Request $request)
     {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'old_password'=>'required',
                    'new_password'=>'required|min:6',
                    'user_id'=>'required',
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $user  = User::where([['status',1],['password',md5($request->old_password)],['id',$request->user_id]])->get()->toArray();
        if (!empty($user)) 
        {
            $newpassword  = User::find($request->user_id);
            $newpassword->password = md5($request->new_password);
            $update     = $newpassword->save();
            if(isset($update))
                return  parent::api_response([], true, 'Password updated successfully' , 200);
            else
                return  parent::api_response([], false, 'Something went wrong', 200);
        }
        else
        {
            return  parent::api_response([], false, 'Old password is incorrect', 200);
        } 
     }

     public function saveIllness(Request $request)
     {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id'=>'required',
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        if(!empty($request->diagnosed_illness))
            { 
                    if(is_numeric($request->diagnosed_illness)== 1)
                {
                    $user                      = User::find($request->user_id);
                    $user->diagnosed_illness   = $request->diagnosed_illness;
                    $saveuser         = $user->save();
                }
                else
                {

                    $illness          = new Illness();
                    $illness->name    = trim($request->diagnosed_illness);
                    $illness->status  = 2;
                    $illness->save();
                    $lastinserted_id  = $illness->id;

                    if(!empty($lastinserted_id))
                    {
                        $user                      = User::find($request->user_id);
                        $user->diagnosed_illness   = $lastinserted_id;
                        $saveuser                  = $user->save();
                    }
                    
                }
            }
            else
            {
                return  parent::api_response([],false,'There is nothing to save.', 200);
            }
                    

                    
                
                 if (isset($saveuser)) 
                 {
                    return  parent::api_response($user,true,'Illness saved successfully.', 200);
                    
                 }
     }

        public function getAllTestByUser(Request $request)
        {
            $input=$request->all(); 
            $validator=Validator::make($input,[
                    'user_id'=>'required',
                    ]);
        
            if($validator->fails())
            {
                
                return  parent::api_response([], false, $validator->errors()->first(), 200);
                
            }

            $year =  date("y"); 
            $current = $year;
            $year  -= 0;
          for ($i = 0; $i < 3; $i++) {
            if (($year+$i) == $current)
           $newyear[] =   $year+$i;
            else
             $newyear[]= $year+$i;

          
          }
        // foreach ($newyear as $value) 
        // {
        //     $result[] = Result::where([['status',1],['user_id',$request->user_id],['years',$value]])->select('id as test_id','color_and_value','date','years')->get()->toArray();
        // }


        $result = Result::where([['status',1],['user_id',$request->user_id]])->select('id as test_id','color_and_value','date','years')->orderBy('id', 'desc')->get()->toArray();

        if(!empty($result))
       {
            $years = array_column($result, 'years');
        $years = array_values(array_unique($years));
         
         foreach ($result as $key => $value) 
         {
                 $var = $value['date'];
                 $converted_date =  implode(".", array_reverse(explode("-", $var)));
                  $value['date'] = $converted_date;
                   $result1[] = $value['date'];
         }
        foreach ($result1 as $key => $value) 
        {
                        
                        unset($result[$key]['date']);
                        $result[$key]['date'] = $value;
        }

        $arrname = 'array_';
        for ($i=0; $i < count($years) ; $i++) { 

            $name = "variable{$years[$i]}";
            $$name = array();
            $years_array[$years[$i]] = $$name;
        }

        // print_r($result);

        $finaldata = array();
        foreach ($result as $key => $data) {
            if(in_array($data['years'], $years)){
                $col_val_data = json_decode($data['color_and_value'],true);

                foreach ($col_val_data as $colkey => $value) 
                {
                    if($value['img'] == 'null')
                    { 
                        $col_val_data[$colkey] = $value;
                        $data['short_description'] = '';
                    }
                    else
                    {
                        unset($col_val_data[$colkey]);
                        $data['short_description'] = 'All Ok';
                    }
                }

                $col_val_data = array_values($col_val_data);
                //$data['short_description'] = 'All Ok';
                $data['color_and_value'] = $col_val_data;
                $years_array[$data['years']][] = $data;
            }
        }

       
        foreach ($years_array as $year_key => $value) {
            $finaldata[] = array(
                'year' => '20'.$year_key,
                'yeardata' => $years_array[$year_key],
            );
        }
        $payload=[
            'status' => 'true',
            'message' => 'Test Result',
            'data'=>$finaldata,
        ];
        
       }

       else
       {
            $payload=[
            'status' => 'false',
            'message' => 'Test Result Not Found',
            'data'=>[],
        ];
       }    
       echo  json_encode($payload); 
        
        }


        public function Send_notifiction(Request $request)
        {
            $getUsrId = User::where('id',$request->user_id)->select('gcm_token')->get()->toArray();    
            $message = $request->msg;
            $registrationIds = $getUsrId[0]['gcm_token'];
            if($registrationIds == '')
                return  parent::api_response([], false, 'User Token not found', 200);

            $msg;
            $msg = array(
                'title' => $request->title,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s'),

            );
  
             $fields = array
                        (
                        'to' => $registrationIds,
                        'data' => $msg
                    );
        
            $headers = array
                (

                 'Authorization: key=AAAAuReCT1A:APA91bEXHAG08gC50Aisro4_vpt44e8vYEaQ2m8y2pCnKHd7YjIEqIaYYq_pPMZGITBZsN2nFzHB02UhJ1Dk3iSm4sbJm9lY6EUDizutiLeykf4tk-aLKUGXJ5-1bECkjOs50egFQqNx2ezVucR2ITAAnAJsixDMMg',
                 'Content-Type: application/json'
            );

            #Send Reponse To FireBase Server    
                   $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result = curl_exec($ch );
                curl_close( $ch );
                  
                 if ($result) {
                    $final_data = json_decode($result,true);
                     
            if ($final_data['success']) {

                // $json["success"] = true;
               $payload= array(
                    'success' => true,
                    'message' => 'Notification send Successfully !',
                    'data' => $final_data
                );
                echo  json_encode($payload);
            } else {
                $json["success"] = FALSE;
                    $json["message"] = "Notication not send";
                }
            } else {
                $json["success"] = FALSE;
                $json["message"] = "Something went wrong";
            }


        }

        public function getEditedChart(Request $request)
        {
             $input=$request->all(); 
        $validator=Validator::make($input,[
                    'result_id'=>'required',
                    ]);
        
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

            $get_product_id = Result::join('product','result.product_id','=','product.id')
                                    ->join('brand_categories','product.brand_id','=','brand_categories.id')
                                    ->where('result.id',$request->result_id)->select('result.product_id','result.color_and_value','result.image','product.product_name','brand_categories.brand_name','result.date')->get()->toArray();                     
            $product_id = $get_product_id[0]['product_id'];
             $get_chart    = TestColor::
                            join('assay', 'test_color_value.assay_id', '=', 'assay.id')
                            ->join('product', 'test_color_value.product_id', '=', 'product.id')
                            ->join('brand_categories','product.brand_id','=','brand_categories.id')
                            ->select('test_color_value.assay_id','test_color_value.product_id','test_color_value.color_code_and_value','assay.assay_short as assay_name', 'product.product_name','brand_categories.brand_name')
                            ->where([['test_color_value.product_id',$product_id],['test_color_value.status',1]])
                            ->get()
                            ->toArray();
            foreach ($get_chart as $chartkey => $colorvalue) 
            {
                $getobject = $colorvalue['color_code_and_value'];
                $getArray  = json_decode($getobject,true);

                $get_chart[$chartkey]['color_code_and_value'] = $getArray;

            }
            $var = $get_product_id[0]['date'];
            
            $date = str_replace('.', '-', $var);
            $date_formate =  date('d.m.Y', strtotime($date));
             $json_decode = json_decode($get_product_id[0]['color_and_value'],true);

             $payload=[
                'status' => true,
                'message' => 'Selected Results',
                'brand_name' =>$get_product_id[0]['brand_name'] ,
                'full_chart'=>$get_chart,
                'product_name' =>$get_product_id[0]['product_name'] ,
                'product_id' =>$product_id,
                'date' =>$date_formate ,
                'selecte_data'=>$json_decode,
                'img'    => $get_product_id[0]['image']
              
             ];
             echo json_encode($payload);
        }


}