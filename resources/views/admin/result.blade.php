@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
  <!-- MOdel Start -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      
      <div class="modal-content">
     
        <!-- Modal Header -->
        <!-- <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div> -->
        
        <!-- Modal body -->
        <!-- <div class="modal-body">
          Modal body..

        </div> -->
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  <!-- Model End -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">User Results</h1>
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
                                 <!--   <div class="form-group" style="text-align: right">
                                  <span><b>Search</b></span>
                                  <input type="text" class="form-controller" id="search" name="search">
                                   
                                  </div> -->
                                  {{ Form::open(array('url' => '/filter_result','method' => 'get',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }}
                                  <div class="col-sm-2"><p style="float: left"><b>Date</b><input class="form-control" type="text" id="datepicker" name="date"></p></div>

                                  <div class="col-sm-2">
                                    <p><b>Select User</b>
                                      <select name="user_id" class="form-control">
                                    <option>Select User</option>
                                    @foreach($user as $userdata)
                                    <option value="{{$userdata['id']}}">{{$userdata['name']}}</option>
                                    @endforeach
                                    </select>
                                    </p>
                                  </div>
                                  <div class="col-sm-2"><button style="margin-top: 18px" type="submit" class="btn btn-primary">Generate</button></div>
                                  
                                 
                      {{ Form::close() }}

                      @if(!empty($filter))
                      <div class="col-sm-6">
                                    <div class="col-sm-6"></div>
                                     <div class="col-sm-3 text-right" ><a href="{{url('downloadAllResult/xls/'.$filter['user_id']. '/'.$filter['date'])}}"> <button class = "btn btn-primary">EXCEL<i style = "color:white;padding-left:10px;" title="Download Excel" class="fa fa-download " style="font-size:30px;"></i></button></a></div>
                                  <div class="col-sm-3 text-right" ><a href="{{url('downloadAllResult/csv/'.$filter['user_id']. '/'.$filter['date'])}}"> <button class = "btn btn-primary">CSV<i style = "color:white;padding-left:10px;" title="Download Excel" class="fa fa-download " style="font-size:30px;"></i></button></a></div>
                    </div>

                    @else


                    <div class="col-sm-6">
                                    <div class="col-sm-6"></div>
                                     <div class="col-sm-3 text-right" ><a href="{{url('downloadAllResult/xls')}}"> <button class = "btn btn-primary">EXCEL<i style = "color:white;padding-left:10px;" title="Download Excel" class="fa fa-download " style="font-size:30px;"></i></button></a></div>
                                  <div class="col-sm-3 text-right" ><a href="{{url('downloadAllResult/csv')}}"> <button class = "btn btn-primary">CSV<i style = "color:white;padding-left:10px;" title="Download Excel" class="fa fa-download " style="font-size:30px;"></i></button></a></div>
                    </div>
                    @endif
                                  <div class="row">
                                  <div class="col-lg-12">
                                     <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Brand</th>
                                                <th>Product</th>
                                                <th>Date</th>
                                                <th>Result</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                          @if(isset($result) && !empty($result))
                                          @foreach($result as $res)
                                            <tr>
                                               
                                                <td>{{$res->name}}</td>
                                                <td>{{$res->email}}</td>
                                                <td>{{$res->brand_name}}</td>
                                                <td>{{$res->product_name}}</td>
                                                <td>{{$res->date}}</td>
                                                <td><a class="modalLink" data-id ='{{$res->id}}' data-toggle="modal" data='1' data-target="#myModal" href="">View</a></td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                              No Data Found
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                  </div>
                                   

                                   <div>{{$result->links()}}</div>
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
$('.modalLink').click(function(){
    var test_id=$(this).attr('data-id');
    var data={'_token':"{{ csrf_token()}}",'test_id':test_id};
    $.ajax({

      type : 'get',
      url:'{{url('loadresult')}}',
      data:data,
      cache:false,success:function(data){
        $(".modal-content").html(data);
    }});
});
</script>
@endsection
  

