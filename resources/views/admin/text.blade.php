@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Text</h1>
                    </div>
                   <!--  <div class="form-group" style="text-align: right">
                                  <a  href="{{ URL::to('downloadExcel/xls') }}"><i title="Download Excel" class="fa fa-download" style="font-size:48px;padding-right: 50px"></i></a>



                                   
                                  </div> -->
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->  
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading ">
                              <div class="row">
                               <div class="col-sm-10"> 
                               <!-- <b>Add new Assay here</b> --> 
                               </div> 
                                <div class="col-sm-2"> 
                                    <a href="{{url('showaddtext')}}" class="btn btn-success btn-md">Add text</a>
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
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Text</th>
                                                <th>Action</th>
                                                
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                          @if(isset($data) && !empty($data))
                                          @foreach($data as $text)
                                            <tr class="odd gradeX">
                                                <td>{{$text['text']}}</td>
                                                <td><a href="{{url('edit_text/'.$text['id'])}}"><img src="{{asset('/images/feedbin-icon.png')}}"></a>
                                                 <a href="{{url('del_text/'.$text['id'])}}" onclick="return confirm('Are you sure?')"><img src="{{asset('/images/delete-512.png')}}"></a></td>
                                            </tr>
                                            @endforeach
                                            @endif

                                            
                                          
                                        
                                            
                                        </tbody>
                                    </table>
                                </div>
                                
                                
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
 
url : '{{URL('Showassay')}}',
 
data:{'search':$value},
success:function(data){
$('tbody').empty().html(data);
 
}
 
});
 
 
 
})
 
</script>

<script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });</script>

@endsection
  

