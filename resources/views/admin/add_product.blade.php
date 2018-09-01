@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Product</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                add new product here
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-9">
                                       {{ Form::open(array('url' => '/add_product','method' => 'post',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }}
                                           
                                            <div class="form-group">
                                                <label>Brand Category</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <select class="form-control" name="category_id" id="category_id" required>
                                                       <option value="">Select Brand</option> 
                                                    @foreach ($category_data as $cat)
                                                       <option value="{{$cat['id']}}">{{$cat['brand_name']}}</option> 
                                                    @endforeach
                                                </select>
                                                
                                                <div class="help-block with-errors "></div>
                                            </div>
                                           
                                            
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" class="form-control" required="true" name="name" value="">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success btn-md">Submit</button>
                                            <a href="{{url('showproduct')}}" class="btn btn-danger btn-md">Cancel</a>
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
  

