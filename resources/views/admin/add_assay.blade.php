@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Assay</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                           
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-9" style="margin-left: 15px">
                                        {{ Form::open(array('url' => '/addassay','method' => 'post',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }}
                                       
                                            <div class="form-group">
                                                <label>Assay Short</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input class="form-control" name="assay_short" id="category_name" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Assay Long</label>
                                                <input class="form-control" name="assay_long" id="category_name" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Unit</label>
                                                <input class="form-control" name="unit" id="category_name" required>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                        
                                            
                                            <button type="submit" class="btn btn-success btn-md">Submit</button>
                                            <a href="{{url('Showassay')}}" class="btn btn-danger btn-md">Cancel</a>
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
  

