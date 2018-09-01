@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edit Category</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                edit category here
                            </div>
                            <div class="panel-body">
                                <div lass="row">
                                    <div class="col-lg-9">
                                        <form data-toggle="validator" enctype="multipart/form-data" role="form" method="post" action="{{route('update_category_record')}}" id="form">
                                            <div class="form-group">
                                                <label>Category Name</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="category_id" value="{{$category_record['id']}}" id="category_id">
                                                <input class="form-control" name="name" id="category_name" required value="{{$category_record['name']}}">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" rows="3" name="description" id="category_description" value="">{{$category_record['description']}}</textarea>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            <div class="form-group">
                                                <label> Category Image</label>
                                                <br/><br/>
                                                @if( empty($category_record['id']))
                                                <img src="{{asset('images/no_image.png')}}" id="img1" name="image_preview" height="100px" width="100px">
                                                @else
                                                <img src="{{asset('images/category_images/'.$category_record['image'])}}" id="img1" name="image_preview" height="100px" width="100px">
                                                @endif
                                                <br/><br/>
                                                <input type="file" name="image"   id="image" accept="image/*">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success btn-md">Submit</button>
                                            <a href="{{url('categories')}}" class="btn btn-danger btn-md">Cancel</a>
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
  

