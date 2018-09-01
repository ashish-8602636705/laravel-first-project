@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
  
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Popular Test</h1>
                    </div>
                  
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
                                  {{ Form::open(array('url' => '/filter_popular_test','method' => 'get',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }}

                                  <div class="col-sm-2"><p style="float: left"><b>Start Date</b><input class="form-control" type="text" id="datepicker" name="start_date" required></p></div>

                                  <div class="col-sm-2"><p style="float: left"><b>End Date</b><input class="form-control" type="text" id="datepicker1" name="end_date" required></p></div>

                                  <div class="col-sm-2"><button style="margin-top: 18px" type="submit" class="btn btn-primary">Generate</button>
                                  </div>
                      {{ Form::close() }}
                                  <div class="row">
                                  <div class="col-lg-12">
                                     <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                
                                                <th>Product</th>
                                                <th>Brand</th>
                                                <th>No Of Test</th>
                                               

                                            </tr>
                                        </thead>
                                        <tbody>
                                          @if(isset($data) && !empty($data))
                                          @foreach($data as $product) 
                                            <tr>
                                               
                                                <td>{{$product->product_name}}</td>
                                                <td>{{$product->brand_name}}</td>
                                                <td>{{$product->pcount}}</td>
                                                
                                                 
                                            </tr>
                                             @endforeach
                                            
                                           
                                          @endif
                                        
                                        </tbody>
                                    </table>
                                </div>
                                  </div>
                                   

                                   <!-- <div>Willl be link here</div> -->
                                </div>
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
   <!--  <script type="text/javascript">
 
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

<script>$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });</script> -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script type="text/javascript">
  
$('#dropdownYear').each(function() {

  var year = (new Date()).getFullYear();
  var current = year;
  year -= 1;
  for (var i = 0; i < 6; i++) {
    if ((year+i) == current)
      $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
    else
      $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
  }

})

</script> -->
<script> 
  $( "#datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',//check change
    changeMonth: true,
    changeYear: true
});
  </script>
<script> 
  $( "#datepicker1" ).datepicker({
    dateFormat: 'yy-mm-dd',//check change
    changeMonth: true,
    changeYear: true
});
  </script>

@endsection
  

