@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Diagnosed Illness</h1>
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
                            
                               </div> 
                                <div class="col-sm-2"> 
                                    <a href="{{url('load_illness')}}" class="btn btn-success btn-md">Add Illness</a>
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
                                                <th>name</th>
                                                <th>Action</th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                         @foreach($illness as $ill)

                                            <tr class="odd gradeX">
                                                <td>{{$ill->name}}</td>
                                                @if($ill->status == 1)
                                                <td class="center"><a href="{{'edit_illness/'.$ill->id}}"><img src="{{asset('/images/feedbin-icon.png')}}" ></a> <a href="{{url('del_illness/'.$ill->id)}}" onclick="return confirm('Are you sure?')"><img src="{{asset('/images/delete-512.png')}}"></a></td>
                                                @else
                                                <td><a href="{{url('active/'.$ill->id)}}" onclick="return confirm('Are you sure?')" class="btn btn-success btn-md">Activate</a>
                                                  <a href="{{url('del_illness/'.$ill->id)}}" onclick="return confirm('Are you sure?')"><img src="{{asset('/images/delete-512.png')}}"></a>
                                                </td>
                                                @endif
                                               
                                            </tr>
                                           @endforeach
                                        
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div> {{ $illness->links()}}</div>
                                
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
 
url : '{{URL('showillness')}}',
 
data:{'search':$value},
success:function(data){
$('tbody').empty().html(data);
 
}
 
});
 
 
 
})
 
</script>

<script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });</script>

@endsection
  

