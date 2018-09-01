<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Assay;
use App\Result;
use App\User;
use DB;
use Excel;
//use Maatwebsite\Excel\Concerns\FromCollection;
class ResultController extends Controller
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
    public function index(Request $request)
    { 
        $data['user'] = User::where([['status',1],['id','<>',1]])->select('id','name') ->orderBy('name', 'ASC')->get()->toArray();
        $data['result'] = Result::join('users','result.user_id','=','users.id')
                        ->join('product','result.product_id','=','product.id')
                        ->join('brand_categories','product.brand_id','=','brand_categories.id')
                        ->select('users.name','users.email','result.*','product_name','brand_categories.brand_name')
                        ->where([['result.status',1],['users.status',1]])
                        ->orderBy('result.id', 'desc')
                        ->paginate('10')
                        ;
                if($request->ajax())
                {
                    $id = $request->test_id;
                    $test_data = Result::join('users','result.user_id','=','users.id')
                         ->join('product','result.product_id', '=','product.id')
                         ->join('brand_categories','product.brand_id', '=','brand_categories.id')
                        ->select('users.name','users.email','result.*','product.product_name','brand_categories.brand_name','result.image')
                        ->where([['result.status',1],['users.status',1],['result.id',$id]])->get()->toArray();
                        $color = $test_data[0]['color_and_value'];
                        $image = array($test_data[0]['image']);

                        $json  = json_decode($color,true);
                      foreach ($json as $key => $value) 
                      {
                          $assay  = Assay::where([['status',1],['id',$value['assay_id']]])->select('assay_short','unit')->get()->toArray();
                        $result[] = array_merge($assay[0], $value);
                         
                    }

                    //*****************important data************
                      //<a  href="'.URL('downloadExcel/xls').'/'.$test_data[0]['id'].'"><i title="Download Excel" class="fa fa-download" style="font-size:30px;padding-right: 50px"></i></a>
                    //*******************************************
                        $output = "";
                 $output =   '<div class="modal-header">
                                           <h4 style = "float:left" class="modal-title">'.$test_data[0]['name'].'</h4>
                                           <h4 style = "float:left;margin-left : 15px;" class="modal-title">('.$test_data[0]['date'].')</h4>
                                           <div class="form-group" style="text-align: right">
                                <a  href="'.URL('downloadExcel/xls').'/'.$test_data[0]['id'].'"> <button class = "btn btn-primary">EXCEL<i style = "color:white;padding-left:10px;" title="Download Excel" class="fa fa-download " style="font-size:30px;padding-right: 50px"></i></button></a>
                                 <a  href="'.URL('downloadExcel/csv').'/'.$test_data[0]['id'].'"> <button class = "btn btn-primary">CSV<i style = "color:white;padding-left:10px;" title="Download Excel" class="fa fa-download " style="font-size:30px;padding-right: 50px"></i></button></a>

                                  </div>
                                           </br>
                                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                                         </div>
                             <div class="modal-body"> 
                                <button class ="btn btn-default" style= "background-color:#366c6f;color:white;font-size:16px;float:left">'.$test_data[0]['product_name'].'</button>

                                <button class ="btn btn-default" style= "background-color:#366c6f;color:white;font-size:16px;float:left;margin-left:15px;">'.$test_data[0]['brand_name'].'</button>
                             </div>
                             </br>
                             </br>
                             <div style = "float:center">
                                <table class="table table-striped table-bordered ">'
                                ?>

                                <?php 
                                if(isset($result))
                                { $output2 = array();
                                    foreach ($result as $key => $res) 
                                    {
                                        
                                        $output .=  '<tr>
                                        <td style = "text-align:center">'.$res["assay_short"].'</td>
                                        <td><input style = "background-color:'.$res["bg_color"].';color:'.$res["text_color"].';text-align:center;width:35%;height:40px;padding:10px;margin-left:10px" class = "form-control" type = "text" readonly value = "'.$res["value"].'" size = "2"></td>
                                        <td style = "text-align:center">'.$res["unit"].'</td>
                                        <tr> ';?>
                                  <?php       
                                    }


                                
                                
                             $output .=  '</table>

                             </div>
                             <hr>
                             <div style = "float:center">'
                             ?>
                             <?php
                             if($image[0] != '')
                             {
                              $output .= '<img class ="img-responsive" style = "width:45%;height:350px;margin-left:150px;"  src="images/'.$image[0].'">
                              <br>';
                             }

                            $output .= '</div>
                            
                             <div class="modal-footer" style = "border-top:none !important">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>';
                             }
                        return $output ;

                                 
                               
                         
                }
                $data['filter'] = array();
    	return view('admin.result',$data);
    }   

    public function exportToExcel($type,$id)
    {
        $data  = Result::join('users','result.user_id','=','users.id')
                        ->join('product','result.product_id','=','product.id')
                        ->join('brand_categories','product.brand_id','=','brand_categories.id')
                        ->select('result.id','users.name','users.email','product.product_name','result.date','result.product_id','result.user_id','result.color_and_value','brand_categories.brand_name')
                        ->where([['result.status',1],['users.status',1],['result.id',$id]])->get()->toArray();
        $encodedData = $data[0]['color_and_value'];
        $decodeData  = json_decode($encodedData,true);
        $new_data = array();
         foreach ($decodeData as $key => $value) 
         {  
            $new_data[$key]['Test Id'] = $data[0]['id'];
            $new_data[$key]['Name']    = $data[0]['name'];
            $new_data[$key]['Email']   = $data[0]['email'];
            $new_data[$key]['Brand']   = $data[0]['brand_name'];
            $new_data[$key]['Product']   = $data[0]['product_name'];
            $new_data[$key]['Assay Name'] = $value['assay_name'];
            $new_data[$key]['Value'] = $value['value'];
            $new_data[$key]['Unit'] = $value['unit'];
            $new_data[$key]['Date']   = $data[0]['date'];
         }
            return Excel::create('User Test Report', function($excel) use ($new_data) {
            $excel->sheet('mySheet', function($sheet) use ($new_data)
            {
                $sheet->fromArray($new_data);
            });
           })->download($type);

       
    }

    public function filterResult(Request $request)
    {
      if(is_numeric($request->user_id) && $request->date != '')
      {

        $where = array('users.status'=>1,
                       'result.status'=>1,
                       'result.date'=>$request->date,
                       'users.id'   =>$request->user_id
                        );

      }
      elseif(is_numeric($request->user_id))
      {
        $where = array('users.status'=>1,
                       'result.status'=>1,
                       'users.id'=>$request->user_id,
                        );
      }
      elseif($request->date != '')
      {
        $where = array('users.status'=>1,
                      'result.status'=>1,
                      'result.date'=>$request->date,
                        );
      }
      else
      {
        $where = array('users.status'=>1,
                       'result.status'=>1,
                        );
      }

       if($request->date != '' && is_numeric($request->user_id))
       {
         
          $data['filter'] = array('date'=>$request->date,'user_id' =>$request->user_id); 
          
       }
       elseif(is_numeric($request->user_id) && $request->user_id != '')
       {
         $data['filter'] = array('date'=>0,'user_id' =>$request->user_id);
       }
       elseif($request->date != '')
       {
         $data['filter'] = array('date'=>$request->date,'user_id' =>0);
       }
       else
       {
        $data['filter'] = array();
       } 
      
       //echo "<pre>";print_r($data['filter']);die;

      $data['user'] = User::where([['status',1],['id','<>',1]])->select('id','name') ->orderBy('name', 'ASC')->get()->toArray();
      $data['result'] = Result::join('users','result.user_id','=','users.id')
                        ->join('product','result.product_id','=','product.id')
                        ->join('brand_categories','product.brand_id','=','brand_categories.id')
                        ->select('users.name','users.email','result.*','product_name','brand_categories.brand_name')
                        ->where($where)
                        ->paginate('10');  
           return view('admin.result',$data); 
    }


    public function allUserResultExportToExcel($type)
    {
         $data   = Result::join('users','result.user_id','=','users.id')
                        ->join('product','result.product_id','=','product.id')
                        ->join('brand_categories','product.brand_id','=','brand_categories.id')
                        ->select('users.name','users.email','result.*','product_name','brand_categories.brand_name')
                        ->where([['result.status',1],['users.status',1]])
                        ->get()
                        ->toArray();
           
        foreach ($data as $key => $value) 
        {
          $decodeData[] = json_decode($value['color_and_value'],true);
        }
        $main_array_count = count($data);

        $i = 0;
        foreach ($decodeData as $key => $value) 
         {  

          foreach ($value as $value1) {
            
            $main[] = array('Test Id'=>$data[$i]['id'],'Name'=>$data[$i]['name'],'Email'=>$data[$i]['email'],'Brand'=>$data[$i]['brand_name'],'Product'=>$data[$i]['product_name'],'Assay Name'=>$value1['assay_name'],'Value'=>$value1['value'],'Unit'=>$value1['unit'],'Date'=>$data[$i]['date']);

         }
         $i++;
       }
      return Excel::create('User Test Report', function($excel) use ($main) {
            $excel->sheet('mySheet', function($sheet) use ($main)
            {
                $sheet->fromArray($main);
            });
           })->download($type);
    }


    public function filterAllUserResultExportToExcel($type,$user_id,$date)
    {
         if(is_numeric($user_id) && $date != '' && $user_id != 0 && $date != 0)
          {
               
            $where = array('users.status'=>1,
                           'result.status'=>1,
                           'result.date'=>$date,
                           'users.id'   =>$user_id
                            );
          }
          elseif(is_numeric($user_id) && $user_id != 0)
          {
             
            $where = array('users.status'=>1,
                           'result.status'=>1,
                           'users.id'=>$user_id,
                            );
          }
          elseif($date != 0)
          { 
            $where = array('users.status'=>1,
                          'result.status'=>1,
                          'result.date'=>$date,
                            );
          }
          else
          {
             
            $where = array('users.status'=>1,
                           'result.status'=>1,
                            );
          }
          $data   = Result::join('users','result.user_id','=','users.id')
                        ->join('product','result.product_id','=','product.id')
                        ->join('brand_categories','product.brand_id','=','brand_categories.id')
                        ->select('users.name','users.email','result.*','product_name','brand_categories.brand_name')
                        ->where($where)
                        ->get()
                        ->toArray();
          foreach ($data as $key => $value) 
        {
          $decodeData[] = json_decode($value['color_and_value'],true);
        }
        $main_array_count = count($data);

        $i = 0;
        foreach ($decodeData as $key => $value) 
         {  

          foreach ($value as $value1) {
            
            $main[] = array('Test Id'=>$data[$i]['id'],'Name'=>$data[$i]['name'],'Email'=>$data[$i]['email'],'Brand'=>$data[$i]['brand_name'],'Product'=>$data[$i]['product_name'],'Assay Name'=>$value1['assay_name'],'Value'=>$value1['value'],'Unit'=>$value1['unit'],'Date'=>$data[$i]['date']);

         }
         $i++;
       }
      return Excel::create('User Test Report', function($excel) use ($main) {
            $excel->sheet('mySheet', function($sheet) use ($main)
            {
                $sheet->fromArray($main);
            });
           })->download($type);
    }
}