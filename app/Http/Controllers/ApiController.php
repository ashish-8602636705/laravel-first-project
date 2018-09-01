<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Picture;
use App\Category;
use Illuminate\Support\Facades\DB;


class ApiController extends Controller
{
    public function index(){
        $most_popular_image_array = Picture::where('image_type','=','1')->get()->toarray();
        if(isset($most_popular_image_array)&&!empty($most_popular_image_array)){
                foreach($most_popular_image_array as $key=>$value){
                    $most_popular_image_array[$key]['image']= url('/images/images/'.$value['image']);
                }
            $most_popular_image_array = $most_popular_image_array; 
        }else{
            $most_popular_image_array = [];
        }

         

        $latest_image_array = Picture::where('image_type','=','2')->get()->toarray();
        if(isset($latest_image_array)&&!empty($latest_image_array)){
            foreach($latest_image_array as $key1=>$value1){
                 $latest_image_array[$key1]['image']= url('/images/images/'.$value1['image']);
                }
            $latest_image_array = $latest_image_array; 
        }else{
            $latest_image_array = [];
        }
        
        $category_array = Category::get(['id','name','image','description'])->toarray();
        
        if(isset($category_array)&&!empty($category_array)){
            foreach($category_array as $key2=>$value2){
                $category_array[$key2]['image']= url('/images/category_images/'.$value2['image']);
                }
                
            $category_array = $category_array;
        }else{
            $category_array = [];
        }
        
        $data_array =array(
            'popular_image'=>$most_popular_image_array,
            'latest_image'=>$latest_image_array,
            'categories'=>$category_array,
        );
        
        $response = array(
                'status'=>1,
                'message'=>"Data Retrieved Successfully",
                'data'=>$data_array
        );
        
        echo json_encode($response);
    }
    
    public function getImagesByCategoryId(){
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $json1 = file_get_contents('php://input');
            $json_array = json_decode($json1);
            
            if(isset($json_array)&&!empty($json_array)){
                $category_id = $json_array->category_id;
                if(isset($category_id)&&!empty($category_id)){
                   $picture_record = Picture::where('category_id','=',$category_id)->get()->toarray();
                   if(isset($picture_record)&&!empty($picture_record)){
                       foreach ($picture_record as $key=> $value){
                           
                         $picture_record [$key]['image'] = url('/images/images/'. $value['image']);
                       
                         
                       }
                       
                       //(object)$picture_record;
                       
                    $response = array(
                    'status'=>true,
                    'message'=>"Record retrieved successfully",
                    'data'=>$picture_record
                    );
                    
                   }else{
                     $response = array(
                    'status'=>false,
                    'message'=>"Record not found for this category.",
                    'data'=>[]
                    );
                   }
                }else{
                    $response = array(
                    'status'=>false,
                    'message'=>"Please insert all required fields",
                    'data'=>[]
                    );
                }
                   
            }else{
                $response = array(
                'status'=>false,
                'message'=>"Please insert all required fields",
                'data'=>[]
                );
            }
        }else{
                $response = array(
                'status'=>false ,
                'message'=>"Undefined Method Type",
                'data'=>[]
                );
        }
        echo json_encode($response);   
    }
    
}
