<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Illness;


class IllenessController extends Controller
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
    	$data['illness']    = Illness::where('status',1)->orWhere('status',2)->orderBy('id', 'desc')->paginate('10');
        if($request->ajax())
            {
                $data   =Illness::where([['name','LIKE','%'.$request->search."%"],['status',1]])->orwhere([['name','LIKE','%'.$request->search."%"],['status',2]])->paginate('10');
                if($data)

                {
                    $output = '';
                    foreach ($data as $key => $ill) 
                    {
                    if($ill->status == 1)
                       {
                         $output.='<tr>'.

                    '<td>'.$ill->name.'</td>'. 

                     '<td>'."<a href ='".url('edit_illness/'.$ill->id)."'><img src='".url('/images/feedbin-icon.png')."'> </a>". "<a href ='".url('del_illness/'.$ill->id)."'><img src='".url('/images/delete-512.png')."'> </a>".  ' </td>'.

                    '</tr>';
                       } 
                       else
                       {
                        $output.='<tr>'.

                    '<td>'.$ill->name.'</td>'. 

                     '<td>'."<a href ='".url('active/'.$ill->id)."' class = 'btn btn-success btn-md'>Activate</a>". "<a href ='".url('del_illness/'.$ill->id)."'><img src='".url('/images/delete-512.png')."'> </a>".  ' </td>'.

                    '</tr>';
                       }
                       
                    

                    }


                return Response($output);



                }


            }

    	return view('admin.illness',$data);
    }
    public function loadaddillness()
    {
    	return view('admin.add_illness');
    }

    public function addIllness(Request $request)
    {
    	$illness_name = $request->illness;
    	
    	$illness        = new Illness();

    	$illness->name  = $illness_name;

    	$save           = $illness->save();
    	if (isset($save)) 
    	{
    		return redirect('/showillness')->with('success', 'Illness Added successfully');
    	}
    }

    public function editillness($id)
    {
    	$data['illness']    = Illness::where([['status',1],['id',$id]])->get()->toArray();
    	return view('admin.edit_illness',$data);
    }

    public function updateillness(Request $request)
    {
    	$illness        = Illness::find($request->id);
    	$illness->name  = $request->illness;
    	$save           = $illness->save();
    	if (isset($save)) 
    	{
    		return redirect('/showillness')->with('success', 'Illness Updated Successfully');
    	}
    }

    public function deleteillness($id)
    {
    	$illness          = Illness::find($id);
    	$illness->status  = 0;
    	$save             = $illness->save();
    	if (isset($save)) 
    	{
    		return redirect('/showillness')->with('success', 'Illness Deleted Successfully');
    	}
    }

    public function activeillness($id)
    {
    	$illness          = Illness::find($id);
    	$illness->status  = 1;
    	$save             = $illness->save();
    	if (isset($save)) 
    	{
    		return redirect('/showillness')->with('success', 'Illness Activated Successfully');
    	}
    }

}
