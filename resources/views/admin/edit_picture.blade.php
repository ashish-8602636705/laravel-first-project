@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Edir Picture</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                edit picture here
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <form data-toggle="validator" enctype="multipart/form-data" role="form" method="post" action="{{route('update_picture_record')}}" id="form">
                                              <div class="form-group">
                                                <label>Select Type</label>
                                                <select class="form-control" name="image_type" id="image_type">
                                                       <option @if($picture_data[0]['image_type']=='0'){{"selected"}} @endif value="0">Category</option> 
                                                       <option @if($picture_data[0]['image_type']=='1'){{"selected"}} @endif value="1">Most Popular</option> 
                                                       <option  @if($picture_data[0]['image_type']=='2'){{"selected"}} @endif value="2">Featured</option> 
                                                </select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Category Name</label>
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="picture_id" value="{{ $picture_data[0]['id'] }}">
                                                <select class="form-control" name="category_id" id="category_id">
                                                    @forelse ($category_data as $cat)
                                                    <option value="0">Select Category</option>
                                                       <option value="{{$cat['id']}}" @if($picture_data[0]['category_id']==$cat['id']) {{"selected"}} @endif >{{$cat['name']}}</option> 
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
                                                    <option value="1" @if($picture_data[0]['is_premium']=='1') {{"Selected"}}@endif>Premium</option>
                                                    <option value="2" @if($picture_data[0]['is_premium']=='2') {{"Selected"}}@endif>Basic</option>
                                                </select>
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label> Picture Name</label>
                                                
                                                <input type="text" class="form-control" required name="name" value="{{$picture_data[0]['name']}}">
                                                <div class="help-block with-errors "></div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label> Image</label>
                                                <br/><br/>
                                                
                                                @if (file_exists( public_path()."/images/images/".$picture_data[0]['image']))  
                                                        <img src="{{asset('/images/images/'.$picture_data[0]['image'])}}" id="img1" height="50px" width="50px">
                                                   @else
                                                        <img src="{{asset('/images/no_image.png')}}"  id="img1" height="100px" width="100px">
                                                @endif
                                                <br/><br/>
                                                <input type="file" name="image" id="image" accept="image/*">
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
  

