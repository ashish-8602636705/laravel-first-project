<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    public function api_response($data, $success = true, $message = null, $status = 200){
       if(is_object($data)){

          $data = $data->toArray();
           }else if(empty($data)){
           
          $data=(object)$data;  
        }
        
         $payload=[
                'status' => $success,
                'message' => $message,
                'data'=>$data
             
             ];
        return  json_encode($payload);
    }
    
    public function send_android_notification($deviceToken,$title,$message)
        {

            $registrationIds = $deviceToken;
            #prep the bundle
            $msg = array(
                'title' => $title,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s'),

            );

              $fields = array(
                              "to" => $registrationIds,
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
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
         
         if ($result) 
         {
            $final_data = json_decode($result,true);
             
              if ($final_data['success']) 
              {

                  // $json["success"] = true;
                 $payload= array(
                      'success' => true,
                      'message' => 'Notification send Successfully !',
                      'data' => $final_data
                  );
                   
              } 
              else 
              {
                  $json["success"] = FALSE;
                  $json["message"] = "Notication not send";
              }
          } 
          else 
          {
              $json["success"] = FALSE;
              $json["message"] = "Something went wrong";
          }



       }
}
