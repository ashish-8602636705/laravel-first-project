<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Assay;
use DB;
use Excel;
//use Maatwebsite\Excel\Concerns\FromCollection;
class AssayController extends Controller
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
    	$data['data'] = Assay::where('status',1)->orderBy('id', 'desc')->paginate('10');

        if($request->ajax())
            {
                $data   = Assay::where([['assay_short','LIKE','%'.$request->search."%"],['status',1]])->orWhere([['assay_long','LIKE','%'.$request->search."%"],['status',1]])->orderBy('id', 'desc')->paginate('10');
                if($data)

                {
                    $output = '';
                    foreach ($data as $key => $assay) 
                    {

                    $output.='<tr>'.

                    '<td>'.$assay->assay_short.'</td>'.

                    '<td>'.$assay->assay_long.'</td>'. 
                    '<td>'.$assay->unit.'</td>'.  

                     '<td>'."<a href ='".url('edit_assay/'.$assay->id)."'><img src='".url('/images/feedbin-icon.png')."'> </a>". "<a href ='".url('del_assay/'.$assay->id)."'><img src='".url('/images/delete-512.png')."'> </a>".  ' </td>'.

                    '</tr>';

                    }


                return Response($output);



                }


            }

    	return view('admin.assay',$data);
    }

    public function loadaddassay()
    {
    	return view('admin.add_assay');
    }

    public function addassay(Request $request)
    {
    	$assay              = new Assay();
    	$assay->assay_short = $request->assay_short;
    	$assay->assay_long  = $request->assay_long;
        $assay->unit        = $request->unit;
    	$saveAssay          = $assay->save();

    	if (isset($saveAssay)) 
    	{
    		return redirect('Showassay')->with('success', 'Assay Saved Successfully');
    	}
    }
    	public function loadeditassay($id)
    	{
    		$data['data'] = Assay::find($id)->toArray();
    		return view('admin.edit_assay',$data);
    	}

    	public function updateAssay(Request $request)
    	{
    		$id                   = $request->assay_id;
    		$assay                = Assay::find($id);
    		$assay->assay_short   = $request->assay_short;
    		$assay->assay_long    = $request->assay_long;
            $assay->unit          = $request->unit;
    		$updateassay          = $assay->save();
    		if (isset($updateassay)) 
    		{
    			return redirect('Showassay')->with('success','Assay Updated Successfully');
    		}
    	}

    	public function deleteAssay($id)
    	{
    		$assay                = Assay::find($id);
    		$assay->status        = 0;
    		$updateassay          = $assay->save();
    		if (isset($updateassay)) 
    		{
    			return redirect('Showassay')->with('success','Assay Deleted Successfully');
    		}
    	}

        public function exportToExcel($type)
        {
            $data  = Assay::where('status',1)->select('assay_short as Assay Short','assay_long as Assay Long')->get()->toArray();
           
            return Excel::create('laravelcode', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
           })->download($type);
        }
    
}
