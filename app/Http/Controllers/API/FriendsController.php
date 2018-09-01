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
use App\Friend;
use App\Share;
use App\Result;
use Mail;
use DB;
use \Crypt;

class FriendsController extends APIController
{
    public function getAlluser(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id' =>'required',
                    ]);
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $friends = Friend::where('user_id',$request->user_id)
                ->select('friend_id')
                ->get()
                ->toArray();

       $column_id     =  array_column($friends, 'friend_id');
       array_push($column_id, $request->user_id);
       $user = User::whereNotIn('id',$column_id)->get()->toArray();

        if (isset($user) && !empty($user)) 
        {
            return  parent::api_response($user, true, 'All Users', 200);
        }
        else
        {
            return  parent::api_response([], false, 'No User Found', 200);
        } 
    }

    public function RequestSend(Request $request)
    {

        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id' =>'required',
                    'friend_id'=>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $isvaliduser   = User::where([['id',$request->user_id],['status',1]])->count();
        $isvalidfriend = User::where([['id',$request->friend_id],['status',1]])->count();

        if ($isvalidfriend >0 && $isvaliduser >0) 
        {
            $is_exist_request = Friend::where([['user_id',$request->user_id],['friend_id',$request->friend_id]])->get()->toArray();
            if(!empty($is_exist_request))
            { 
                 if ($is_exist_request[0]['is_friend'] == 0 || $is_exist_request[0]['is_friend'] == 3) 
                {
                    $update_isfriend  = Friend::find($is_exist_request[0]['id']);
                    $update_isfriend->is_friend = 2;
                    $update           = $update_isfriend->save();
                    if ($update)
                     { 

                        
                        return  parent::api_response(array('request_id'=>$is_exist_request[0]['id']), true, 'Request sent successfully', 200);
                     } 
                 }
                else
                {
                    return  parent::api_response([], false, 'Allready friends or requested', 200);
                } 
            }
           else
            {
                

                $readyTOSendRequest = new Friend;
                $readyTOSendRequest->user_id = $request->user_id;
                $readyTOSendRequest->friend_id = $request->friend_id;
                $readyTOSendRequest->is_friend = 2;
                $readyTOSendRequest->request_sent_date = date('m-d-y');
                $update          = $readyTOSendRequest->save();
                $request_id      = $readyTOSendRequest->id;

                if($update)
                {
                    $getUsrId = User::where('id',$request->friend_id)->select('gcm_token','name')->get()->toArray();
                    $getUsrnme = User::where('id',$request->user_id)->select('name')->get()->toArray();
                    $title = 'Friend Request';
                    
                    $message = $getUsrnme[0]['name']." wants to friend with you on puri.";


                         $payload=[
                    'status' => true,
                    'message' => 'Request sent successfully !',
                    'data'=>array('request_id'=>$request_id)];
                echo json_encode($payload);

                parent::send_android_notification($getUsrId[0]['gcm_token'],$title,$message);

                }
                else
                    return  parent::api_response([], false, 'Failed to send request', 200);

            } 
           
        }
        else
        {
            return  parent::api_response([], false, 'invalid user or friend', 200);
        } 



    }

    public function accept_request(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'request_id' =>'required',
                    'isAccepted' =>'required',
                    
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }


        else
        {
            $getUserAndFrndId = Friend::where('id',$request->request_id)->select('user_id','friend_id')->get()->toArray();

            $getUsrId = User::where('id',$getUserAndFrndId[0]['user_id'])->select('gcm_token')->get()->toArray();

            $getUsrnme = User::where('id',$getUserAndFrndId[0]['friend_id'])->select('name')->get()->toArray();

            

            $getAllIds    = Friend::select('id')->get()->toArray();
            $column_id     =  array_column($getAllIds, 'id');
            $exist         = in_array($request->request_id, $column_id);

            if($exist != 1)
            {
                return  parent::api_response([], false, 'Invalid request id', 200);
            }

            $allready_accepted  = Friend::where([['id',$request->request_id],['is_friend',1]])->count();
            if($allready_accepted >0 && $request->isAccepted == 1)
            {
                return  parent::api_response([], false, 'Allready you are friend with this user', 200);
            }

            else
            {

                $friend             = Friend::find($request->request_id);
                if($request->isAccepted == 1)
                $friend->is_friend  = 1;
                else
                 $friend->is_friend  = 3;   
                $update_status      = $friend->save();

            if($update_status)
            {

                $mutual         = Friend::where('id',$request->request_id)->select('user_id','friend_id')->get()->toArray();

                $newuser_id   = $mutual[0]['friend_id'];
                $newfriend_id = $mutual[0]['user_id'];

                $mutual_exist  = Friend::where([['user_id',$newuser_id],['friend_id',$newfriend_id]])->select('id')->get()->toArray();

                if (!empty($mutual_exist)) 
                {
                    $friend            = Friend::find($mutual_exist[0]['id']);
                    if($request->isAccepted == 1)
                    {
                        $friend->is_friend = 1;
                        $mutual_accepted   = $friend->save();
                    }
                    
                    else
                    {
                      $friend->is_friend = 3;
                      $mutual_accepted   = $friend->save();

                      if($mutual_accepted)  
                      { 
                        $find_share_id = Share::where([['status',1],['friends_id',$newuser_id],['user_id',$newfriend_id]])->select('id')->get()->toArray();
                        if(!empty($find_share_id))
                        {
                             foreach ($find_share_id as $key => $value) 
                            {
                                $data[] = $value['id'];
                            }
                            DB::enableQueryLog();
                            $update =  DB::table('share_table')
                                        ->whereIn('id', $data)
                                        ->update(['status' => 0]);
                        }
        
                       
                             
                        
                      }
                    }
                      
                    

                    if($request->isAccepted == 1)
                    {
                        // return  parent::api_response([], true, 'Request accepted !', 200);
                         $payload=[
                            'status' => true,
                            'message' => 'Request accepted !',
                            'data'=>array()];
                     
                        $title = 'Request Accepted';
                        $message = $getUsrnme[0]['name']." accepted your friend request on puri.";
                    }
                    
                    else
                    {
                     
                         $payload=[
                            'status' => true,
                            'message' => 'Request Cancelled !',
                            'data'=>array()];
           
                     $title = 'Request Cancelled';
                    $message = $getUsrnme[0]['name']." cancelled your friend request on puri.";
                    }

                       
                }
                else
                {
                $friend            = new Friend;
                $friend->user_id   = $newuser_id;
                $friend->friend_id = $newfriend_id;
                if($request->isAccepted == 1)
                {
                    $friend->is_friend = 1;
                    $mutual_update     = $friend->save();
                }
                
                else
                {
                    $friend->is_friend = 3; 
                    $mutual_update     = $friend->save();   
                    if($mutual_update)  
                      { 
                        $find_share_id = Share::where([['status',1],['friends_id',$newuser_id],['user_id',$newfriend_id]])->select('id')->get()->toArray();

        
                        foreach ($find_share_id as $key => $value) 
                        {
                            $data[] = $value['id'];
                        }
                        DB::enableQueryLog();
                        $update =  DB::table('share_table')
                                    ->whereIn('id', $data)
                                    ->update(['status' => 0]);
                      }                
                }
                  

                

                if($request->isAccepted == 1)
                {
                    // return  parent::api_response([], true, 'Request accepted !', 200);
                     $payload=[
                    'status' => true,
                    'message' => 'Request accepted !',
                    'data'=>array()];
               
                    $title = 'Request Accepted';
                    $message = $getUsrnme[0]['name']." accepted your friend request on puri.";
                }
                    
                else
                {
                     // return  parent::api_response([], true, 'Request Cancelled !', 200);
                     $payload=[
                    'status' => true,
                    'message' => 'Request cancelled ',
                    'data'=> array()
                ];

                
                     $title = 'Request Cancelled';
                    
                     $message = $getUsrnme[0]['name']." cancelled your friend request on puri.";
                }
                }
                echo  json_encode($payload);
                parent::send_android_notification($getUsrId[0]['gcm_token'],$title,$message);
            }

              else
              {
                 return  parent::api_response([], false, 'something went wrong!', 200);
              } 
            }
           
        } 

    }

    public function getAllfriends(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id' =>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
         {
           $Allfriend = User::join('friends','users.id','=','friends.friend_id')
                        ->select('users.id','users.name','friends.share_all')
                        ->where([['friends.user_id',$request->user_id],['friends.is_friend',1],['users.status',1]])
                        ->orwhere([['friends.user_id',$request->user_id],['friends.is_friend',1],['users.status',1]])
                        ->get()
                        ->toArray();
            if(isset($Allfriend) && !empty($Allfriend))
            {
                return  parent::api_response($Allfriend, true, 'All friends list', 200);
            }    
            else
            {
                return  parent::api_response([], false, 'You have no friends connected. Please add a friend to share your test results', 200);
            }    
         }   
    }

    public function searchUser(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'username' =>'required',
                    'user_id' =>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
           {
             // $user = User::join('friends','users.id','=','friends.friend_id')
             //       ->where([['users.name','LIKE','%'.$request->username."%"],['users.status',1],['users.id','<>',1]])->orWhere([['users.email','LIKE','%'.$request->username."%"],['users.status',1],['users.id','<>',1]])->
             // select('users.*','friends.is_friend')->get()->toArray();
             // print_r($user);die;

              $user = Friend::where([['user_id',$request->user_id],['is_friend',1]])->select('friend_id')->get()->toArray();
             
             // if(!empty($user))
             // {
             //    foreach ($user as $key => $value) 
             //  {
             //        if($value['is_friend'] != 0 &&$value['is_friend'] != 3 )
             //          $exist_id[] = $value['id'];
             //        else
             //           $exist_id[] = 1;     
             //  }
             //               }
             // else
             // {
             //    $exist_id[] = 1;
             // } 
              if(!empty($user))
              {
                foreach ($user as $key => $value) 
                {
                  $exist_id[]  = $value['friend_id'];
                } 
              }
              else
              {
                $exist_id[] = 1;
              } 


            $getAllUserNotexistInabove  = User::whereNotIn('id',$exist_id)
                    ->where([['name','LIKE','%'.$request->username."%"],['status',1],['id','<>',1]])
                    ->get()
            ->toArray();

            if(empty($getAllUserNotexistInabove))

                $getAllUserNotexistInabove  = User::whereNotIn('id',$exist_id)
                    ->Where([['email','LIKE','%'.$request->username."%"],['status',1],['id','<>',1]])
                    ->get()
            ->toArray();

            if(!empty($getAllUserNotexistInabove) && isset($getAllUserNotexistInabove))
             return  parent::api_response($getAllUserNotexistInabove, true, 'Serch user', 200);
            else
             return  parent::api_response([], false, 'No data found', 200);
           } 
    }

    public function unfriend(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id'   =>'required',
                    'friend_id' =>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        else
       {
            $get_id = Friend::where([['is_friend',1],['user_id',$request->user_id],['friend_id',$request->friend_id]])->select('id','is_friend')->get()->toArray();
           
            if(isset($get_id)  && !empty($get_id) && isset($get_id[0]['is_friend']) && $get_id[0]['is_friend'] == 1)
            {
                
                $friend              = Friend::find($get_id[0]['id']);
                $friend->is_friend   = 0;
                $unfriend            = $friend->save();

                if(isset($unfriend))
                {
                    $mutual_id = Friend::where([['is_friend',1],['friend_id',$request->user_id],['user_id',$request->friend_id]])->select('id','is_friend')->get()->toArray();
                    $mutual            = Friend::find($mutual_id[0]['id']);
                    $mutual->is_friend = 0;
                    $unfriend_mutual   = $mutual->save();
                    if (isset($unfriend_mutual)) 
                    {
                        return  parent::api_response([], true, 'Unfriend successfully', 200);
                    }
                    else
                       {
                        return  parent::api_response([], false, 'Failed to unfriend', 200);
                       } 

                }
            }
            else
               {
                return  parent::api_response([], false, 'Something went wrong!', 200);
               } 
       } 
    }

    public function cancelRequest(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'request_id' =>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $status   = Friend::where([['id',$request->request_id],['is_friend',2]])->select('id')->get()->toArray();
        if (!empty($status)) 
        {
            $cancelRequest = Friend::find($request->request_id);
            $cancelRequest->is_friend = 3;
            $Request_cancel      = $cancelRequest->save();

            if ($Request_cancel) 
            {
                 return  parent::api_response([], true, 'Request cnecelled successfully', 200);
            }
            else
            {
                 return  parent::api_response([], false, 'Failed to cancel request', 200);
            } 
        }
        else
           {
            return  parent::api_response([], false, 'Invalid requset id', 200);
           } 
        
    }

    public function inviteFriend(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'email' =>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        else
        {
            $is_exist_email  = User::where([['status',1],['email',$request->email]])->get()->toArray();
             if (!empty($is_exist_email)) 
             {
                return  parent::api_response([], false, 'User all ready exist please send request', 200);
             }
             else
             {
                $friend_mail        = $request->email;
                $data['title']      = "Invitation TO Join Puri App";
                $data['user_email'] = Crypt::encryptString($request->email);
                    $mail =  Mail::send('admin.inviteFriend', $data, function($message) use ($friend_mail) {
                        $message->to($friend_mail, 'Receiver Name')->subject('Health App');
                    });

                    return  parent::api_response([], true, 'Invitation sent Successfully', 200);
             } 

        } 

    }

    public function getAllRequest(Request $request)
    {
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id' =>'required',
                    ]);

        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        $getAllRequest = Friend::where([['friend_id',$request->user_id],['is_friend',2]])->select('id','user_id')->get()->toArray();
        if(!empty($getAllRequest))
        {
            foreach ($getAllRequest as $value) 
            {
                $AllfriendId[] = $value['user_id'];
                $allrequested_id[] = $value['id'];
            }


             $All_request = User::where([['status',1]])->whereIn('id',$AllfriendId)->select('id','name','email')->get()->toArray();
              foreach ($allrequested_id as $key => $value) 
              {
                  $All_request[$key]['request_id'] = $value;
              }

             if(!empty($All_request))
                return  parent::api_response($All_request, true, 'All Request', 200);
            else
                return  parent::api_response([], false, 'No Data Found', 200);
        }
     else
       {
        return  parent::api_response([], false, 'No Data Found', 200);

       } 
        
    }

    public function share_result_with_friend(Request $request)
    { 
        $friends_id = $request->friends_id;
        foreach ($friends_id as $key => $value) 
        {
            $all_ids[] = $value['id'];
        }
    
        $username = User::where([['status',1],['id',$request->user_id]])->select('name')->get()->toArray();

        $get_gcm = User::where('status',1)->whereIn('id',$all_ids)->select('gcm_token','name')->get()->toArray();
        foreach ($get_gcm as $key => $value) 
        {
            $all_gcm[] = $value['gcm_token'];
        }

        $title   = 'Share Result';
        $message = "Result Shared By ".$username[0]['name'];
        //if Richard wants to share by result id please check backup of date 08/08/2018 .......
        $checkAllReadyShared = Share::where([['status',1],['user_id',$request->user_id],['result_id',$request->shared_result_id]])->select('friends_id')->get()->toArray();
       
        if(!empty($checkAllReadyShared))
        {
            foreach ($checkAllReadyShared as $key => $value) 
            {
                $all_exist_id[] = $value['friends_id']; 
            }
            $result = array_map('unserialize', array_diff(array_map('serialize', $all_ids), array_map('serialize', $all_exist_id)));
            if(!empty($result))
            {
                foreach ($result as $key => $value) 
                {   
                    $share = new Share();
                    $share->user_id = $request->user_id;
                    $share->result_id = $request->shared_result_id;
                    $share->friends_id = $value;
                    $share_result =  $share->save();
                }

                if($share_result)
                {
                    $payload=[
                        'status' => true,
                        'message' => 'Result shared with friends',
                        'data'=>[]
                     
                     ];
                 
                }
            }
            else
            {
                $payload=[
                    'status' => true,
                    'message' => 'Result shared with friends',
                    'data'=>[],
                 
                 ];
            }
        }

        else
           {

             foreach ($all_ids as $key => $value) 
                {   
                    $share = new Share();
                    $share->user_id = $request->user_id;
                    $share->result_id = $request->shared_result_id;
                    $share->friends_id = $value;
                    $share_result =  $share->save();
                }
                if($share_result)
                {
                    $payload=[
                        'status' => true,
                        'message' => 'Result shared with friends',
                        'data'=>[]
                     
                     ];
                 
                }
           } 
        
    //may be chage in above code..................................................................
        
        
        
        echo  json_encode($payload);
        $this->send_result_notification($all_gcm,$title,$message); 
        
    }
    
    public function send_result_notification($deviceToken,$title,$message)
        {
            $registrationIds = $deviceToken;
            #prep the bundle
            $msg = array(
                'title' => $title,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s'),

            );
            foreach ($registrationIds as $key => $value) 
            {
                $fields = array(
                              "to" => $value,
                              "data"=> $msg,
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
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );
        $result = curl_exec($ch );
        curl_close( $ch );
       
        }
        

       }

       public function getFriendsReport(Request $request)
       { 
             $getAllids = Share::where([['status',1],['friends_id',$request->user_id]])->select('user_id')->get()->toArray();

               if(!empty($getAllids))
               {
                    foreach ($getAllids as $key => $value) 
                     {
                         $id[] = $value['user_id'];
                     }
                     $uniqIds = array_unique($id);

                      $isfriend  = Friend::where([['is_friend',1],['friend_id',$request->user_id]])->whereIn('user_id',$uniqIds)->get()->toArray();
                     if(empty($isfriend))
                     {
                         return  parent::api_response([], false, 'No Data Found', 200);
                     }
                     else
                    {
                         foreach ($isfriend as $key => $value)
                         {   
                              $allsharedFriendId[] = $value['user_id'];
                         }
                    } 

                        
                     $getAlluserlist =  User::where([['status',1]])->whereIn('id',$allsharedFriendId)->select('id as user_id','name')->get()->toArray();
                 
                 if(!empty($getAlluserlist))
                    return  parent::api_response($getAlluserlist, true, 'Shared With Users', 200);
                else
                    return  parent::api_response([], false, 'No Data Found', 200);
               }
               else
               {
                return  parent::api_response([], false, 'No Data Found', 200);
               }
                 
       }

       public function getShareResult(Request $request)
       {

            $getAllResultId  = Share::Where([['status',1],['user_id',$request->friend_id],['friends_id',$request->user_id]])->select('result_id')->orderBy('id', 'desc')->get()->toArray();

        if(!empty($getAllResultId))
        {
                foreach ($getAllResultId as $key => $value)
             {
                 $resultId[] = $value['result_id'];
             }

              $result = Result::where('status',1)->whereIn('id',$resultId)->select('id as test_id','color_and_value','date','years')->orderBy('id','desc')->get()->toArray();

              
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
                'message' => 'No Result Found',
                'data'=>[],
            ];
           }    
     }
     else
     {
        $payload=[
                'status' => 'false',
                'message' => 'No Result Found',
                'data'=>[],
            ];
     }
             
       echo  json_encode($payload); 
       }
      
       public function shareAllResult(Request $request)
       { 
        $input=$request->all(); 
        $validator=Validator::make($input,[
                    'user_id' =>'required',
                    'friend_id' =>'required',
                    'share_all' =>'required'
                    ]);
        if($validator->fails())
        {
            return  parent::api_response([], false, $validator->errors()->first(), 200); 
        }

        if($request->share_all == 1)
        { 
            $getfirstFriendId = Friend::Where([['user_id',$request->user_id],['friend_id',$request->friend_id],['is_friend',1]])
                       ->get()->toArray();
            if(!empty($getfirstFriendId))  
            {
                $makeSharableFriend =  Friend::find($getfirstFriendId[0]['id']);
                $makeSharableFriend->share_all = 1;
                $update             = $makeSharableFriend->save();
            }       
            
        }
       else
        {
            $getAllSharedResult = Share::Where([['user_id',$request->user_id],['friends_id',$request->friend_id],['status',1]])->select('id')->get()->toArray();
             if(!empty($getAllSharedResult))
        {
                $unshareAllresultBychangeStatusOFfriend = Friend::Where([['user_id',$request->user_id],['friend_id',$request->friend_id],['share_all',1]])->get()->toArray();

             if(!empty($unshareAllresultBychangeStatusOFfriend))
             {
                $Unshare = Friend::find($unshareAllresultBychangeStatusOFfriend[0]['id']);
                $Unshare->share_all = 0;
                $Unshare->save();
             }  
             else
             {
                return  parent::api_response([], false, 'Allready Unshared All Result .', 200);
             }
            foreach ($getAllSharedResult as $key => $value) 
            {
               $newArray[] = $value['id']; 
                
            }
            $removeShare = DB::table('share_table')
                             ->whereIn('id',$newArray)
                             ->update(['status' => 0]);
             
            if($removeShare)
                return  parent::api_response([], true, 'Unshare Result Successfully !', 200);
            else
                return  parent::api_response([], false, 'Something Went Wrong ', 200);
        }
        else
           {
            return  parent::api_response([], false, 'No Result Found To Share', 200);
           } 
            

        } 
        


        $user_name = User::where([['status',1],['id',$request->user_id]])->select('name')->get()->toArray();
        $friend_gcm = User::where([['status',1],['id',$request->friend_id]])->select('gcm_token')->get()->toArray();
          
        $title = 'Result Shared';
        $message = "Result Share By".$user_name[0]['name'];
        $gcm =  $friend_gcm;
        $getAllResultOfFriend = Result::Where([['status',1],['user_id',$request->user_id]])->select('id as result_id')->get()->toArray();
         $getAllReadyshared = Share::Where([['status',1],['user_id',$request->user_id],['friends_id',$request->friend_id]])->select('result_id')->get()->toArray();

             foreach ($getAllResultOfFriend as $key => $value) 
              {
                  $newdata[] = $value['result_id'];
              }

                if(!empty($getAllReadyshared))
                {
                  foreach ($getAllReadyshared as $key => $value) 
                  {
                      $allreadyExist[] = $value['result_id'];
                  }
                  $newArray = array_diff($newdata, $allreadyExist);
                  if(count($newArray > 0))
                    {    
                        foreach ($newArray as $key => $value) 
                        {
                           $finalIds[$key]['result_id'] = $value;
                        }
                         
                    }
                   else
                   {
                    $finalIds = $getAllResultOfFriend;
                   }
                }
                else
                { 
                    $finalIds = $getAllResultOfFriend;
                }
              
            if(!empty($finalIds))
            {
                foreach ($finalIds as $key => $value) 
                 {
                      $share = new Share();
                      $share->user_id = $request->user_id;
                      $share->friends_id = $request->friend_id;
                      $share->result_id  = $value['result_id'];
                      $result_shared = $share->save();
                 }
                 if ($result_shared) 
                 {
                      $payload=
                      [
                        'status' => 'true',
                        'message' => 'All Result Shared',
                        'data'=>[],
                     ];
                 }
            }
             else
            { 
                $payload=
                      [
                        'status' => 'true',
                        'message' => 'All Result Shared',
                        'data'=>[],
                     ];
            } 
         
         echo  json_encode($payload);
         $this->send_result_notification($gcm,$title,$message);
       }
      
      public function removeShare(Request $request)
      {
        $input=$request->all(); 
        $validator=Validator::make($input,
                   [
                    'user_id' =>'required',
                    'friend_id' =>'required',
                    'result_id' =>'required',
                   ]);
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }

        $ceckResultIdValidOrNot = Share::Where([['status',1],['user_id',$request->user_id],['friends_id',$request->friend_id],['result_id',$request->result_id]])->count();
         if($ceckResultIdValidOrNot >0)
         {
            $removeShare = DB::table('share_table')
                             ->where([['status',1],['user_id',$request->user_id],['friends_id',$request->friend_id],['result_id',$request->result_id]])
                             ->update(['status' => 0]);
            if(isset($removeShare))
                return  parent::api_response([], true, 'Result Remove From User Successfully !', 200);
            else
                return  parent::api_response([], false, 'Something Went Wrong', 200);
         }
         else
        {
          return  parent::api_response([], false, 'Invalid Result Id', 200);
        } 
      }

      public function shareAllResultByStatus(Request $request)
      {
        $input=$request->all(); 
        $validator=Validator::make($input,
                   [
                    'user_id' =>'required',
                    'friend_id' =>'required',
                    'share_all' =>'required',
                   ]);
        if($validator->fails())
        {
            
            return  parent::api_response([], false, $validator->errors()->first(), 200);
            
        }
        $getfirstFriendId = Friend::Where([['user_id',$request->user_id],['friend_id',$request->friend_id],['is_friend',1]])
                       ->get()->toArray();
       
            $makeSharableFriend =  Friend::find($getfirstFriendId[0]['id']);
            $makeSharableFriend->share_all = 1;
            $update             = $makeSharableFriend->save();
                     
         if(isset($update))
                return  parent::api_response([], true, 'All Result Shared Successfully !', 200);
            else
                return  parent::api_response([], false, 'Something Went Wrong', 200);
      }
}