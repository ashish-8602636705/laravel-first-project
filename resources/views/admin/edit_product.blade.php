@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">       <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit Product</h1>
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
                                    <div class="col-lg-9">
                                      {{ Form::open(array('url' => '/update_product','method' => 'post',"class"=>"form-horizontal")) }}
                                            
                                            <div class="form-group">
                                                <label>Brand Name</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="product_id" value="{{$brand_data[0]->id}}">
                                                <select class="form-control " name="brand_id" id="category_id">
                                                       <option value="{{$brand_data[0]->brand_id}} @if(!empty($product_data[0])){{"selected"}} @endif">{{$brand_data[0]->brand_name}}</option>
                                                      
                                                       @foreach($all_brand as $brand)

                                                       <option value="{{$brand['id']}}">{{$brand['brand_name']}}</option>
                                                       @endforeach
                                                   
                                                   
                                                </select>
                                            </div>
                                           
                                            
                                            <div class="form-group">
                                                <label> Product Name</label>
                                                
                                                <input type="text" class="form-control" name="product_name" value="{{$brand_data[0]->product_name}}">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success btn-md">Update</button>
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
  

