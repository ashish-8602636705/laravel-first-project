@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Test</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div style="margin-left: 15px" class="col-lg-9">
                                        {{ Form::open(array('url' => '/addtest','method' => 'post',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }}
                                       
                                           <!--  <div class="form-group">
                                                <label>Test Name</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="product_id" value="{{$product_id}}">
                                                <input type="text" class="form-control" name="test_name" required>
                                                <div class="help-block with-errors "></div>
                                            </div> -->
                                             <div class="form-group">
                                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="product_id" value="{{$product_id}}">
                                                <label>Assay Name</label>
                                                <select class="form-control" name="assay_id" required>
                                                       <option value="">Select Assay</option> 
                                                    @foreach ($data as $cat)
                                                       <option value="{{$cat['id']}}">{{$cat['assay_long']}}</option> 
                                                    @endforeach
                                                </select>
                                                
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                        
                                            
                                            <button type="submit" class="btn btn-success btn-md">Submit</button>
                                            <a href="{{url('view/'.$product_id)}}" class="btn btn-danger btn-md">Cancel</a>
                                       {{ Form::close() }}
                                    </div>
                                    
                                </div>
                                <!-- /.row (nested) -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
@endsection
@section('foot')     
<script>
$("#image").change(function(){  
  
var input=this;  
  if (input.files && input.files[0]) {
    var a=input.files[0].name;
    var reader = new FileReader();
    reader.onload = function (e) {
          var image = new Image();   
          image.src = e.target.result;
          image.onload = function () {
                var height = this.height;
                var width = this.width;
                $('#img1').prop('src', e.target.result);  
          };

          
    }
    reader.readAsDataURL(input.files[0]);
  }

}); 
</script>
@endsection
  

