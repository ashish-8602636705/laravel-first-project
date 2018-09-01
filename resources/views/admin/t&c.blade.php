@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Terms And Conditions</h1>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div>
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
                    </div>
                        </div>

                    </div>
                    
                
              {{ Form::open(array('url' => '/save_t&c','method' => 'post',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }}
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($input))
                        <textarea class="form-control" id="summary-ckeditor" name="terms">{{$input[0]['input_data']}}</textarea>
                        @endif
                    </div>
                    <div style="margin-left: 18px">
                        <button style="margin-top: 18px;font-size: 16px" type="submit" class="btn btn-primary">Save</button>
                    </div>
                     
                      
                    
                    
                </div>
                {{ Form::close() }}
            </div>
<input type="hidden" name="_token" id="csrf_id" value="{{ csrf_token() }}">
@endsection
@section('foot')     
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'summary-ckeditor' );

</script>

@endsection
  

