<?php

namespace App\Http\Controllers;
use App\Picture;
use App\User;
use App\Brandcategories;
use App\Product;
use App\Image;
use App\Text;
use \Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;


class UsersController extends Controller
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
    	 $record = DB::table('users')
	            ->where([['status','=','1'],['id','<>',1]])
                ->orderBy('id', 'desc')
	            ->paginate('10');

            $data['data'] = $record;

            if($request->ajax())
            {
                $data   =DB::table('users')->where([['name','LIKE','%'.$request->search."%"],['status',1],['id','<>',1]])->orWhere([['email','LIKE','%'.$request->search."%"],['status',1],['id','<>',1]])->paginate('10');
                if($data)

                {
                    $output = '';
                    foreach ($data as $key => $users) 
                    {

                    $output.='<tr>'.

                    '<td>'.$users->name.'</td>'.

                    '<td>'.$users->email.'</td>'.            
                    '<td>'."<a href ='".url('del_user/'.$users->id)."'><img src='".url('/images/delete-512.png')."'> </a>".'</td>'.

                    '</tr>';

                    }



                return Response($output);



                }


            }
           return view('admin.users',$data);

       
    }

    public function deleteuser($id)
    {
    	$user         = User::find($id);
    	$user->status = 0;
    	$update       = $user->save();

    	if ($update) 
    	{
    		 return redirect('users')->with('success', 'User Deleted Successfully');
    	}

    }

    public function loadAddImage(Request $request)
    {
        $getImage['image'] = Image::where('status',1)->select('id','img','img_description')->get()->toArray();

        return view('admin.addimage',$getImage);
    }
    public function showimageadd()
    {
        return view('admin.add_image');
    }
    public function add_image(Request $request)
    { 
       $img = $request->file('img');
       $img_desc = $request->img_name;
       $checkImgNameAllreadyExist = Image::where([['status',1],['img_description','LIKE','%'.$request->img_name."%"]])->count();
       if($checkImgNameAllreadyExist>0)
       {
            return redirect()->back()->with('success', 'This image name allready exist');
       }
       $imageName = time().'.'.$img->getClientOriginalExtension();
       $img->move(public_path('images'), $imageName);
       $images       = new Image();
       $images->img  = $imageName;
       $images->img_description  = $img_desc;
       $upload_image = $images->save();
       if($upload_image)
       {
        return redirect('loadaddimage')->with('success', 'Image Saved Successfully');
       } 
       
    }

    public function delete_image($id)
    {
         $delete          = Image::find($id);
         $delete->status  = 0;
         $delete_image    = $delete->save();
         if ($delete_image) 
         {
             return redirect('loadaddimage')->with('success', 'Image Deleted Successfully');
         }
    }

    public function load_short_text(Request $request)
    {
        $getText['data'] = Text::where('status',1)->select('id','text')->get()->toArray();

         return view('admin.text',$getText);
    }

    public function loadAddText(Request $request)
    {
        return view('admin.add_text');
    }
    public function saveText(Request $request)
    {
        $text   = new Text();
        $text->text = $request->text;
        $saveText   = $text->save();
        if ($saveText) 
        {
            return redirect('loadshorttext')->with('success', 'Text Added Successfully');
        }
    }
    public function deleteText($id)
    {
        $delete = Text::find($id);
        $delete->status = 0;
        $delete_text = $delete->save();
        if ($delete_text) 
        {
            return redirect('loadshorttext')->with('success', 'Text Deleted Successfully');
        }
    }

    public function editText($id)
    {
        $get_text['text'] = Text::where([['status',1],['id',$id]])->select('id','text')->get()->toArray();
        return view('admin.edit_text',$get_text);
    }
    public function updateText(Request $request)
    {
         $text = Text::find($request->id);
         $text->text = $request->text;
         $update_text =$text->save();
         if ($update_text) 
         {
             return redirect('loadshorttext')->with('success', 'Text Updated Successfully');
         }
    }

}
