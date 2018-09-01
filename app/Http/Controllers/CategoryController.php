<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
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
        $data = Category::get()->toarray();
        if(isset($data)&&!empty($data)){
        $data['category_record'] = $data;
        }else{
        $data['category_record'] = [];    
        }
        return view('admin.category_list',$data);
    }
    
    public function deleteCategory(Request $req){
        $id = $req->input('id');
        $category_record = Category::where('id','=',$id)->get()->toarray();
        
        if(isset($category_record[0])&&!empty($category_record[0])){
            
           $image_path = public_path() . "/images/category_images/" .$category_record[0]['image'];
           if(file_exists($image_path)){
               unlink($image_path);
           }
           
           //File::Delete(public_path() . "/images/category_images/" .$category_record[0]['image']);
           //$res_del = Storage::delete(public_path().'/images/category_images/'.$category_record[0]['image']);
        }
        
       echo  $res = Category::where('id','=',$id)->delete();
       //session(['success' => 'Record deleted successfully!']);
    }
   
    
      public function getCategoryRecord(Request $req){
        $start  =  $req->input('start'); 
        $length =  $req->input('length');
        $draw   =  $req->input('draw');
        $search =  $req->input('search');
        $order  =  $req->input('order');
    }
    
    
    public function loadViewAddCategory(Request $req){
   
        return view('admin.add_category');
    }
    
    public function AddCategory(Request $req){
      $name = $req->input('name');
        
      $description = $req->input('description');
              
      $image = $req->file('image');
       
      /*image upload code goes here*/
      $this->validate($req, [
         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|',
       ]);

       $input['imagename'] = time().'.'.$image->getClientOriginalName();
       $destinationPath = public_path('/images/category_images/');
       $image->move($destinationPath, $input['imagename']);
     /*image upload code goes here*/ 
        /*insert record code goes here */
        $category = new Category;

        $category->name = $name;
        $category->description = $description;
        $category->image = $input['imagename'];
        $category->save();
        $category->id;
        return redirect('/categories')->with('success', 'Category added successfully');
    }
    
    public function editViewLoadCategory($category_id,Request $req){
        
        $category_record = Category::where('id','=',$category_id)->get()->toarray();   
        $data = [];
        if($category_record[0]){
           $data['category_record'] = $category_record[0]; 
        }else{
           $data['category_record'] = []; 
        }
        
        return view('admin.edit_category',$data);
    }
    
    public function editCategory(Request $req){
         $category_id = $req->input('category_id');
        
         $name = $req->input('name');
        
         $description = $req->input('description');
        
        
        $image = $req->file('image');
        
        if(isset($image)){
       /*image upload code goes here*/
       $this->validate($req, [
         'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|',
       ]);

       $input['imagename'] = time().'.'.$image->getClientOriginalName();
       $destinationPath = public_path('/images/category_images/');
       $image->move($destinationPath, $input['imagename']);
       
        $res = Category::where('id','=',$category_id)->update(['name'=>$name,'description'=>$description,'image'=>$input['imagename']]);

     /*image upload code goes here*/ 
        /*insert record code goes here */
       }else{
           $res = Category::where('id','=',$category_id)->update(['name'=>$name,'description'=>$description]);
        }
      return redirect('/categories')->with('success', 'Category updated successfully');     
    }
}
