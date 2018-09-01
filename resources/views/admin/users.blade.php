@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Users List</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading ">
                              <div class="row">
                               <div class="col-sm-10"> 
                                <b>All Users</b>
                               </div> 
                               <!--  <div class="col-sm-2"> 
                                    <a href="{{url('load_add')}}" class="btn btn-success btn-md">Add Product</a>
                               </div> --> 
                              </div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                               <center>
                                @if(session('success'))
                                  <div class="alert alert-success alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>{{ session('success') }}</strong>
                                  </div>
                                                                
                                
                                @endif
                            </center>
                                @if (session('error'))
                                <div class="alert alert-danger">
                                {{ session('error') }}
                                </div>
                                @endif
                                <div class="dataTable_wrapper">
                                 <div class="form-group" style="text-align: right">
                                  <span><b>Search</b></span>
                                  <input type="text" class="form-controller" id="search" name="search">
                                   
                                  </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($data) && !empty($data))
                                            @foreach ($data as $cat)

                                            <tr class="odd gradeX" id="user">
                                                <td>{{$cat->name}}</td>
                                                <td>{{$cat->email}}</td>
                                              
                                                <td class="center"><a href="{{url('del_user/'.$cat->id)}}"  onclick="return confirm('Are you sure?')"><img src="{{asset('/images/delete-512.png')}}"></a></td>
                                                <!-- {{url('edit_picture/'.$cat->id)}} -->
                                            </tr>
                                            @endforeach

                                            @else
                                            <tr>
                                              <td>No Data Found</td>
                                              <td>No Data Found</td>
                                              <td>No Data Found</td>
                                            </tr>

                                        @endif
                                            
                                        </tbody>

                                    </table>

                                </div>

                                 <div> {{ $data->links()}}</div>
                                
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            </div>
<input type="hidden" name="_token" id="csrf_id" value="{{ csrf_token() }}">
@endsection
@section('foot')
  <script type="text/javascript">
 
$('#search').on('keyup',function(){
 
$value=$(this).val();
 
$.ajax({
 
type : 'get',
 
url : '{{URL('users')}}',
 
data:{'search':$value},
success:function(data){
$('tbody').empty().html(data);
 
}
 
});
 
 
 
})
 
</script>

<script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });</script>

@endsection
