@extends('admin.layout.layout')
@section('page')
<div id="page-wrapper">
         <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit profile</h1>
                    </div>
                    <div class="row">
                        <div class="col-lg-1"></div>
                    <div class="col-lg-8">
                         {{ Form::open(array('url' => '/updateinfo','method' => 'post',"class"=>"form-horizontal",'role'=>"form")) }}
                                
                    <center>@if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                   </center>           @if(isset($data))
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input class="form-control" value="{{$data['name']}}" name="name">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="Email" class="form-control" value="{{$data['email']}}" name="email">
                                            </div>

                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" value="" name="password">
                                            </div>
                                    @endif
                                            <div> 
                                                <button class="btn btn-success btn-md" type="submit">Update</button>
                                             
                                            </div>


                       {{ Form::close() }}
                </div>
                </div>
                <!-- /.row -->
               
 </div>
</div>

@endsection