@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Add Picture</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                add new picture here
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <form data-toggle="validator" enctype="multipart/form-data" role="form" method="post" action="{{route('insert_picture_record')}}" id="form">
                                            <div class="form-group">
                                                <label>Select Type</label>
                                                <select class="form-control" name="image_type" id="image_type">
                                                       <option value="0">Category</option> 
                                                       <option value="1">Most Popular</option> 
                                                       <option value="2">Featured</option> 
                                                </select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Category Name</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <select class="form-control" name="category_id" id="category_id">
                                                       <option value="0">Select Category</option> 
                                                    @forelse ($category_data as $cat)
                                                       <option value="{{$cat['id']}}">{{$cat['name']}}</option> 
                                                    @empty
                                                    <option value="">No category available</option>
                                                    @endforelse
                                                </select>
                                                
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Image Type</label>
                                                <select class="form-control" required="" name="is_premium">
                                                    <option value="">Select Type</option>
                                                    <option value="1">Premium</option>
                                                    <option value="2">Basic</option>
                                                </select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Picture Name</label>
                                                <input type="text" class="form-control" required="true" name="name" value="">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label> Image</label>
                                                <br/><br/>
                                                <img src="{{asset('images/no_image.png')}}" id="img1" name="image_preview" height="100px" width="100px">
                                                <br/><br/>
                                                <input type="file" name="image" required  id="image" accept="image/*">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success btn-md">Submit</button>
                                            <a href="{{url('pictures')}}" class="btn btn-danger btn-md">Cancel</a>
                                        </form>
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
  

