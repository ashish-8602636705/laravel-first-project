<?php

namespace App\Http\Controllers;
use App\Picture;
use App\Category;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class PictureController extends Controller
{
    public function index(Request $request){
        /*code goes here*/
        $record = DB::table("pictures")
        ->select ("categories.name", "pictures.id", "pictures.image","pictures.name as pic_name","pictures.image_type")
        ->leftjoin("categories", "pictures.category_id", "=", "categories.id")
        ->get()->toarray();
        if(isset($record)&&!empty($record)){
            
            foreach($record as $key=>$value){
                
                if($value->image_type=='1'){
                    $record[$key]->name="Most Popular";
                }elseif($value->image_type=='2'){
                    $record[$key]->name="Featured or Latest";
                }
            }
            
            $data['picture_record'] = $record;
        }else{
            $data['picture_record'] = [];
        }
        //echo "<pre>";print_r($data);die;
       /*code goes here*/
       return view('admin.picture_list',$data);
    }
    
    public function loadViewAddPicture(Request $request){
        $data['category_data'] = Category::get(['id','name'])->toarray();
        return view('admin.add_picture',$data);
    }
    
    public function addPicture(Request $request){
        $category_id = $request->input('category_id');
        $name = $request->input('name');
        $is_premium = $request->input('is_premium');
        $image_type = $request->input('image_type');
        $image = $request->file('image');
       
      /*image upload code goes here*/
      $this->validate($request, [
         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|',
       ]);

       $input['imagename'] = time().'.'.$image->getClientOriginalName();
       $destinationPath = public_path('/images/images/');
       $image->move($destinationPath, $input['imagename']);
     /*image upload code goes here*/ 
        /*insert record code goes here */
        $pic = new Picture;
        $pic->category_id = $category_id;
        $pic->name = $name;
        $pic->is_premium = $is_premium; 
        $pic->image_type=$image_type;
        $pic->image = $input['imagename'];
        $pic->save();
        $pic->id;
        return redirect('/pictures')->with('success', 'Picture added successfully');
    }
    
    public function editViewLoadPicture($picture_id,Request $request){
        $data['picture_data'] = Picture::where("id","=",$picture_id)->get(['id','image','category_id','name','is_premium','image_type'])->toarray();
        $data['category_data'] = Category::get(['id','name'])->toarray();
        //echo '<pre>';print_r($data);die;
        return view('admin.edit_picture',$data);
    }
    
    public function deletePicture(Request $req){
        $id = $req->input('id');
        $picture_record = Picture::where('id','=',$id)->get()->toarray();
        
        if(isset($picture_record[0])&&!empty($picture_record[0])){
            
           $image_path = public_path() . "/images/images/" .$picture_record[0]['image'];
           if(file_exists($image_path)){
               unlink($image_path);
           }
           //File::Delete(public_path() . "/images/category_images/" .$category_record[0]['image']);
           //$res_del = Storage::delete(public_path().'/images/category_images/'.$category_record[0]['image']);
        }
        
       echo  $res = Picture::where('id','=',$id)->delete();
      // session(['success' => 'Record deleted successfully!']);
    }
    
    public function editPicture(Request $req){
        $category_id = $req->input('category_id');
        $picture_id = $req->input('picture_id');
        $is_premium = $req->input('is_premium');
        $name = $req->input('name');
        $image_type = $req->input('image_type');
        $image = $req->file('image');
        
       if(isset($image)){
       /*image upload code goes here*/
           
       $this->validate($req, [
         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|',
       ]);
       
       $input['imagename'] = time().'.'.$image->getClientOriginalName();;
       $destinationPath = public_path('/images/images/');
       $image->move($destinationPath, $input['imagename']);
       
       $res = Picture::where('id','=',$picture_id)->update(['name'=>$name,'image'=>$input['imagename'],'category_id'=>$category_id,'is_premium'=>$is_premium,'image_type'=>$image_type]);

     /*image upload code goes here*/ 
        /*insert record code goes here */
       }else{
           $res = Picture::where('id','=',$picture_id)->update(['name'=>$name,'category_id'=>$category_id,'is_premium'=>$is_premium,'image_type'=>$image_type]);
        }
      return redirect('/pictures')->with('success', 'Picture updated successfully');    
    }
    
}
