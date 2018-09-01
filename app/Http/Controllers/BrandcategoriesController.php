<?php

namespace App\Http\Controllers;
ini_set('memory_limit','-1');
use Illuminate\Http\Request;
use App\User;
use App\Brandcategories;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BrandcategoriesController extends Controller
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
	public function getIndex(Request $request)
	{
        $record = Brandcategories::where('status','=','1')
                ->orderBy('id', 'desc')
                ->paginate('10');
		$data['data'] = $record;

        if($request->ajax())
            {
                $data   =DB::table('brand_categories')->where([['brand_name','LIKE','%'.$request->search."%"],['status',1]])->orWhere([['description','LIKE','%'.$request->search."%"],['status',1]])->paginate('10');
                if($data)

                {
                    $output = '';
                    foreach ($data as $key => $brand) 
                    {

                    $output.='<tr>'.

                    '<td>'.$brand->brand_name.'</td>'.

                    '<td>'.$brand->description.'</td>'.  

                     '<td>'."<a href ='".url('edit_brand/'.$brand->id)."'><img src='".url('/images/feedbin-icon.png')."'> </a>". "<a href ='".url('del_brand/'.$brand->id)."'><img src='".url('/images/delete-512.png')."'> </a>".  ' </td>'.

                    '</tr>';

                    }


                return Response($output);



                }


            }
	    return view('admin.brandcategories_list',$data);
	}


	public function anyData()
    {
        return Datatables::of(Brandcategories::query()->where('status',1)->orderBy('id', 'desc'))->make(true);
    }

    public function addbrandcategories()
    {
    	return view('admin.addbrandcategory');
    }

    public function savebrand(Request $request)
    {
    	$obj              = new Brandcategories;
    	$obj->brand_name  = $request->name;
    	$obj->description = $request->description;
    	$result           = $obj->save();

    	if($result ==  true)
    		 return redirect('allcategories')->with('success', 'Category Added Successfully');

    }
    public function loadeditbrand($id)
    {
    	$data['data'] = Brandcategories::find($id)->toArray();
    	return view('admin.edit_brand',$data);
    }

    public function updatebrand(Request $request)
    {
    	$id                  = $request->id;
    	$brand_name          = $request->brand_name;
    	$description         = $request->description;
  
    	$brand               = Brandcategories::find($id);
    	$brand->brand_name   = $brand_name;
    	$brand->description  = $description;
    	$update              = $brand->save();
    	if($update)
    	{
    		 return redirect('allcategories')->with('success', 'Brand Updated Successfully');
    	}	
    
    }

    public function deletebrand($id)
    {
    	$brand               = Brandcategories::find($id);
    	$brand->status       = 0;
    	$update              = $brand->save();
    	if($update)
    	{
    		 return redirect('allcategories')->with('success', 'Category Deleted Successfully');
    	}	
    }
}
