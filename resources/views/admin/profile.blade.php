@extends('admin.layout.layout')
@section('page')
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Admin Profile</h1>
                    </div>
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                        	<center>
                        		@if (session('success'))
								                        		<div class="alert alert-success alert-dismissible fade in">
								    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								    <strong>{{ session('success') }}</strong>
								  </div>
										                        
		                        
		                        @endif
                        	</center>
					@if ($errors->any())
					    <div style="color: red">
					       
					            @foreach ($errors->all() as $error)
					                {{ $error }}
					            @endforeach
					        
					    </div>
					@endif
                            <div class="panel-heading">
                                Admin Info.
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>email</th>
                                                <th>password</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            	@if(isset($data))
                                                <td>{{$data['name']}}</td>
                                                <td>{{$data['email']}}</td>
                                                <td><b>* * * * * * * *</b></td>
                                                @endif
                                                <td class="center"><a href="{{url('editinfo/'.$data['id'])}}"><img src="{{asset('/images/feedbin-icon.png')}}" ></a></td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-6 -->
                   
                    <!-- /.col-lg-6 -->
                </div>
                </div>
                <!-- /.row -->
               
 </div>

@endsection