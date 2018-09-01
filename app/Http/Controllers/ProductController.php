<?php
namespace App\Http\Controllers;
ini_set('memory_limit','-1');
use App\Picture;
use App\Category;
use App\Brandcategories;
use App\Product;
use App\Result;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ProductController extends Controller
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

     public function index(Request $request){
        $record = DB::table('product')
	            ->join('brand_categories', 'product.brand_id', '=', 'brand_categories.id')
	            ->select('product.*', 'brand_categories.brand_name') 
	            ->where(array('product.status'=>'1','brand_categories.status'=>'1'))  
	             ->orderBy('product.id', 'DESC')    
	            ->paginate('10');
        
        if($request->ajax())
            {
                $data      = DB::table('product')
                ->join('brand_categories', 'product.brand_id', '=', 'brand_categories.id')
                ->select('product.*', 'brand_categories.brand_name') 
                ->where([['product.product_name','LIKE','%'.$request->search."%"],['product.status',1],['brand_categories.status',1]])
                ->orWhere([['brand_categories.brand_name','LIKE','%'.$request->search."%"],['product.status',1],['brand_categories.status',1]])      
                ->paginate('10');
                if($data)

                {
                    $output = '';
                    foreach ($data as $key => $info) 
                    {

                    $output.='<tr>'.

                    '<td>'.$info->product_name.'</td>'.

                    '<td>'.$info->brand_name.'</td>'.  

                     '<td>'."<a href ='".url('edit_product/'.$info->id)."'><img src='".url('/images/feedbin-icon.png')."'> </a>". "<a href ='".url('del_product/'.$info->id)."'><img src='".url('/images/delete-512.png')."'> </a>".  ' </td>'.
                     '<td>'."<a href ='".url('view/'.$info->id)."'>Add Test</a>".'</td>'.

                    '</tr>';

                    }


                return Response($output);



                }


            }
            $data['data'] = $record;
        
       return view('admin.product',$data);
    }
    
    public function loadViewAddProduct(Request $request){
        $data['category_data'] = Brandcategories::where('status',1)->get(['id','brand_name'])->toarray();
        return view('admin.add_product',$data);
    }
    
    public function addproduct(Request $request){
        $category_id = $request->input('category_id');
        $name = $request->input('name');
        $this->validate($request, [
         'category_id' => 'required',
         'name' => 'required'
       ]);
        $product = new Product;
        $product->brand_id = $category_id;
        $product->product_name = $name;
        $product->save();
        $product->id;
        return redirect('/showproduct')->with('success', 'Product added successfully');
    }
    
    public function editViewLoadProduct($product_id,Request $request)
    {

        $data['brand_data'] = DB::table('product')
	            ->join('brand_categories', 'product.brand_id', '=', 'brand_categories.id')
	            ->select('product.*', 'brand_categories.brand_name') 
	            ->where(array('product.status' => '1','product.id'=>$product_id,'brand_categories.status'=>'1'))
	            ->get()
	            ->toArray();
          $data['all_brand'] = Brandcategories::where('status',1)->get()->toArray();
        return view('admin.edit_product',$data);
    }
    
    public function deleteproduct($id){
    	$product         = Product::find($id);
    	$product->status = 0;
    	$update          = $product->save();
    	if(isset($update))
       {
         return redirect('/showproduct')->with('success', 'Product deleted successfully');    
       }
       
    }
    
    public function updateproduct(Request $req)
    {
        $product_id   			= $req->input('product_id');
        $brand_id     			= $req->input('brand_id');
        $product_name 			= $req->input('product_name');

     	$product                = Product::find($product_id);
     	$product->brand_id      = $brand_id;
     	$product->product_name  = $product_name;
        $update                 = $product->save();
       if(isset($update))
       {
       
         return redirect('/showproduct')->with('success', 'Product updated successfully');    
       }
    }

    public function LoadPopularProduct(Request $request)
    {
      
        DB::enableQueryLog();
        $product['data'] = DB::table('result')->join('product', 'result.product_id','=','product.id')
                         ->join('brand_categories','product.brand_id','=','brand_categories.id')
                         ->select('product.product_name','product.id as product_id','brand_categories.brand_name',DB::raw('count(result.product_id) as pcount'))
                         ->where('result.status',1)
                         ->orderBy('result.product_id', 'desc')
                         ->groupBy('result.product_id')
                         ->get()
                         ->toArray();

        return view('admin.populartest',$product);
    }

    public function filter_popular_test(Request $request)
    {
        
        $dates = array($request->start_date,$request->end_date);
        $product['data'] = DB::table('result')->join('product', 'result.product_id','=','product.id')
                         ->join('brand_categories','product.brand_id','=','brand_categories.id')
                         ->select('product.product_name','product.id as product_id','brand_categories.brand_name',DB::raw('count(result.product_id) as pcount'))
                         ->where('result.status',1)
                         ->whereBetween('date',$dates)
                         ->orderBy('result.product_id', 'desc')
                         ->groupBy('result.product_id')
                         ->get()
                         ->toArray();

        // $query = DB::getQueryLog();

        // $query = end($query);

        // dd($query);
        return view('admin.populartest',$product);
                
    }


}