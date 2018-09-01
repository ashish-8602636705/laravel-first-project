@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Product List</h1>
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
                               <b>Add new product here</b> 
                               </div> 
                                <div class="col-sm-2"> 
                                    <a href="{{url('load_add')}}" class="btn btn-success btn-md">Add Product</a>
                               </div> 
                              </div>
                            </div>
                             <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_id">
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
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Brand</th>
                                                <th>Action</th>
                                                <th>View Detail</th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @foreach ($data as $cat)
                                            <tr class="odd gradeX">
                                                <td>{{$cat->product_name}}</td>
                                                <td>{{$cat->brand_name}}</td>
                                                <td class="center"><a href="{{'edit_product/'.$cat->id}}"><img src="{{asset('/images/feedbin-icon.png')}}" ></a> <a href="{{url('del_product/'.$cat->id)}}" onclick="return confirm('Are you sure?')"><img src="{{asset('/images/delete-512.png')}}"></a></td>
                                                <td><a href="{{url('view/'.$cat->id)}}">Add Test</a></td>
                                      
                                                <!-- {{url('edit_picture/'.$cat->id)}} -->
                                            </tr>
                                            @endforeach
                                        
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div>{{$data->links()}}</div>
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
 
url : '{{URL('showproduct')}}',
 
data:{'search':$value},
success:function(data){
$('tbody').empty().html(data);
 
}
 
});
 
 
 
})
 
</script>

<script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });</script>

@endsection
  

