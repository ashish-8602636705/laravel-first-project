@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Test List</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading ">
                              <div class="row">
                               <div class="col-sm-10"> 
                               <b>Add Color And Value Here</b> 
                               </div> 
                                <div class="col-sm-2"> 
                                    <a href="{{url('add_more_test/'.$product_id)}}" class="btn btn-success btn-md">Add More</a>
                               </div> 
                              </div>
                            </div>

                            
                             
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
                                 <!--  {{ Form::open(array('url' => '/updatechart','method' => 'post',"class"=>"form-horizontal",'role'=>"form",'id'=>'form')) }} -->
                                   <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_id">
                                   
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover horizontal-scroll-wrapper squares " id="tableData">
                                        <thead>

                                            <tr>
                                                <th>Test Name</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Action</th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                         @if(isset($data) && !empty($data))
                                            @foreach($data as $rec)
                                            <?php $colorandvalue=json_decode($rec->color_code_and_value,TRUE); 
                                          
                                            ?>


                                            <tr data-toggle="validator" role="form">
                                              <td class="form-group" >
                                                <input type="hidden" name="assay_id" value="{{$rec->assay_id}}">
                                               <input style="text-align: center;border-radius: 10px;margin-top: 30px" type="text" readonly="" value="{{$rec->assay_short}}" size="3">
                                                 <!-- <select style=" -webkit-appearance: none;
                                                  -moz-appearance: none;
                                                  text-indent: 1px;
                                                  text-overflow: '';" disabled class="form-control" name="assay_id">  

                                                       @foreach($assay_data as $assay)
                                                       <option @if($assay['id'] == $rec->assay_id) {{'selected'}}@endif value="{{$assay['id']}}">{{$assay['assay_short']}}
                                                       </option>
                                                       @endforeach  --> 
                                                   
                                                </select>
                                                <input type="hidden" name="row_id" value="{{$rec->id}}">
                                                <input type="hidden" name="product_id" value="{{$product_id}}">
                                              </td>
                                               <td><input class="table_formate" type="Color" name="c1" value="@if(isset($colorandvalue[0]['c1'])){{$colorandvalue[0]['c1']}}@else #fefefe @endif">

                                                    <input class="table_formate" style="color: {{$colorandvalue[0]['tc1']}};background-color: {{$colorandvalue[0]['c1']}};" type="text" name="v1" value="@if(isset($colorandvalue[0]['v1'])){{$colorandvalue[0]['v1']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color1" id="tc1" value="{{$colorandvalue[0]['tc1']}}">
                                                    <input type="hidden" name="unit1" id="unit1" value="{{$colorandvalue[0]['unit1']}}">


                                                    <!-- <input type="text" name="desc1" id="desc1" size="3" value="@if(isset($colorandvalue[0]['description'])){{$colorandvalue[0]['description']}} @endif"> -->

                                                    <div><select class="selectpicker" title="Description" id="desc1" size="2">
                                                      @foreach($text as $t)
                                                      <option @if($t['text'] == $colorandvalue[0]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select></div>
                                                      
                                                    <div><select class="selectpicker" title="Image" id="img1" size="2">
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                        
                                                      
                                                    </select></div>
                                                </td>


                                                 <td><input class="table_formate" type="Color" name="c2" value="@if(isset($colorandvalue[1]['c2'])){{$colorandvalue[1]['c2']}}@else #fefefe @endif">
                                                    <input class="table_formate" style="color: {{$colorandvalue[1]['tc2']}};background-color: {{$colorandvalue[1]['c2']}};" type="text" name="v2" value="@if(isset($colorandvalue[1]['v2'])){{$colorandvalue[1]['v2']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color2" id="tc2" value="{{$colorandvalue[1]['tc2']}}">
                                                    <input type="hidden" name="unit2" id="unit2" value="{{$colorandvalue[1]['unit2']}}">


                                                    <!-- <input type="text" name="desc2" id="desc2" size="3" value="@if(isset($colorandvalue[1]['description'])){{$colorandvalue[1]['description']}} @endif"> -->
                                                   <select class="selectpicker" title="Description" id="desc2" size="2">
                                                      @foreach($text as $t)
                                                      <option @if($t['text'] == $colorandvalue[1]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select>

                                                    <select class="selectpicker" title="Image" id="img2" size="2" >
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                    </select>
                                                </td>



                                                 <td><input class="table_formate" type="Color" name="c3" value="@if(isset($colorandvalue[2]['c3'])){{$colorandvalue[2]['c3']}}@else #fefefe @endif">
                                                    <input class="table_formate" style="color: {{$colorandvalue[2]['tc3']}};background-color: {{$colorandvalue[2]['c3']}};" style="margin-top: 5px" type="text" name="v3" value="@if(isset($colorandvalue[2]['v3'])){{$colorandvalue[2]['v3']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color3" id="tc3" value="{{$colorandvalue[2]['tc3']}}">
                                                    <input type="hidden" name="unit3" id="unit3" value="{{$colorandvalue[2]['unit3']}}">

                                                   <!-- <input type="text" name="desc3" id="desc3" size="3" value="@if(isset($colorandvalue[2]['description'])){{$colorandvalue[2]['description']}} @endif">
                                                    <select class="selectpicker" title="Image" id="img3" size="2"> -->

                                                    <select class="selectpicker" title="Description" id="desc3" size="3">
                                                      @foreach($text as $t)
                                                     <option @if($t['text'] == $colorandvalue[2]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select>

                                                    <select class="selectpicker" title="Image" id="img3" size="2" >
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                    </select>
                                                </td>


                                                <td><input class="table_formate" type="Color" name="c4" value="@if(isset($colorandvalue[3]['c4'])){{$colorandvalue[3]['c4']}}@else #fefefe @endif">
                                                    <input class="table_formate" style="color: {{$colorandvalue[3]['tc4']}};background-color: {{$colorandvalue[3]['c4']}};" style="margin-top: 5px" type="text" name="v4" value="@if(isset($colorandvalue[3]['v4'])){{$colorandvalue[3]['v4']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color4" id="tc4" value="{{$colorandvalue[3]['tc4']}}">
                                                    <input type="hidden" name="unit4" id="unit4" value="{{$colorandvalue[3]['unit4']}}">

                                                  <!--  <input type="text" name="desc4" id="desc4" size="3" value="@if(isset($colorandvalue[3]['description'])){{$colorandvalue[3]['description']}} @endif">
                                                    <select class="selectpicker" title="Image" id="img4" size="2"> -->
                                                    <select class="selectpicker" title="Description" id="desc4" size="2">
                                
                                                      @foreach($text as $t)
                                                      <option @if($t['text'] == $colorandvalue[3]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select>

                                                    <select class="selectpicker" title="Image" id="img4" size="2" >
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                    </select>
                                                </td>


                                                 <td><input class="table_formate" type="Color" name="c5" value="@if(isset($colorandvalue[4]['c5'])){{$colorandvalue[4]['c5']}}@else #fefefe @endif">
                                                    <input class="table_formate" style="color: {{$colorandvalue[4]['tc5']}};background-color: {{$colorandvalue[4]['c5']}};" style="margin-top: 5px" type="text" name="v5" value="@if(isset($colorandvalue[4]['v5'])){{$colorandvalue[4]['v5']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color5" id="tc5" value="{{$colorandvalue[4]['tc5']}}">
                                                    <input type="hidden" name="unit5" id="unit5" value="{{$colorandvalue[4]['unit5']}}">

                                                    <!-- <input type="text" name="desc5" id="desc5" size="3" value="@if(isset($colorandvalue[4]['description'])){{$colorandvalue[4]['description']}} @endif">
                                                    <select class="selectpicker" title="Image" id="img5" size="2"> -->
                                                     <select class="selectpicker" title="Description" id="desc5" size="2">
                                                  
                                                      @foreach($text as $t)
                                                      <option @if($t['text'] == $colorandvalue[4]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select>

                                                    <select class="selectpicker" title="Image" id="img5" size="2" >
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                    </select>
                                                </td>


                                                 <td><input class="table_formate" type="Color" name="c6" value="@if(isset($colorandvalue[5]['c6'])){{$colorandvalue[5]['c6']}}@else #fefefe @endif">
                                                    <input class="table_formate" style="color: {{$colorandvalue[5]['tc6']}};background-color: {{$colorandvalue[5]['c6']}};" style="margin-top: 5px" type="text" name="v6" value="@if(isset($colorandvalue[5]['v6'])){{$colorandvalue[5]['v6']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color6" id="tc6" value="{{$colorandvalue[5]['tc6']}}">
                                                    <input type="hidden" name="unit6" id="unit6" value="{{$colorandvalue[5]['unit6']}}">

                                                    <!-- <input type="text" name="desc6" id="desc6" size="3" value="@if(isset($colorandvalue[5]['description'])){{$colorandvalue[5]['description']}} @endif">
                                                    <select class="selectpicker" title="Image" id="img6" size="2"> -->
                                                      <select class="selectpicker" title="Description" id="desc6" size="2">
                                                     
                                                      @foreach($text as $t)
                                                      <option @if($t['text'] == $colorandvalue[5]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select>

                                                    <select class="selectpicker" title="Image" id="img6" size="2" >
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                    </select>
                                                </td>


                                                 <td><input class="table_formate" type="Color" name="c7" value="@if(isset($colorandvalue[6]['c7'])){{$colorandvalue[6]['c7']}}@else #fefefe @endif">
                                                    <input class="table_formate" style="color: {{$colorandvalue[6]['tc7']}};background-color: {{$colorandvalue[6]['c7']}};" style="margin-top: 5px" type="text" name="v7" value="@if(isset($colorandvalue[6]['v7'])){{$colorandvalue[6]['v7']}}@else - @endif" size="2">
                                                    <input class="table_formate" type="color" name="text_color7" id="tc7" value="{{$colorandvalue[6]['tc7']}}">
                                                    <input type="hidden" name="unit7" id="unit7" value="{{$colorandvalue[6]['unit7']}}">

                                                   <!--  <input type="text" name="desc7" id="desc7" size="3" value="@if(isset($colorandvalue[6]['description'])){{$colorandvalue[6]['description']}} @endif">
                                                    <select class="selectpicker" title="select image" id="img7" size="2"> -->
                                                     <select class="selectpicker" title="Description" id="desc7" size="2">
                                                      @foreach($text as $t)
                                                      <option @if($t['text'] == $colorandvalue[6]['description']) {{'selected'}}@endif value="{{$t['text']}}">{{$t['text']}}</option>
                                                      @endforeach
                                                    </select>

                                                    <select class="selectpicker" title="Image" id="img7" size="2" >
                                                      @foreach($image as $img)
                                                      <option value="{{asset('/images/'.$img['img'])}}"  data-thumbnail="{{asset('/images/'.$img['img'])}}">{{$img['img_description']}}
                                                      </option>
                                                      @endforeach
                                                    </select>
                                                </td>
                                                <td><button type="submit" class="btn btn-success save-color btn-md">Save</button>
                                                <a style="margin-left: 10px;" href="{{url('del_chart/'.$product_id.'/'.$rec->id)}}" onclick="return confirm('Are you sure?')"><img src="{{asset('/images/delete-512.png')}}"></a></td>


                                              
                                            </tr>
                                            @endforeach
                                          @else
                                          <tr>
                                           <div>No data found</div>
                                         </tr>
                                         
                                          @endif
                                            
                                        </tbody>
                                    </table>
                                  </div>
                                    <!-- {{ Form::close() }} -->
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
<script>

var rows = [];
$('body').on('click','#tableData .save-color',function(){
  select=$(this).closest('tr').find('td');

 var test_name = select.eq(0).text();

 var td1=select.eq(1);
 var td2=select.eq(2);
 var td3=select.eq(3);
 var td4=select.eq(4);
 var td5=select.eq(5);
 var td6=select.eq(6);
 var td7=select.eq(7);

 var test_id        = select.eq(0).find('input[name=test_id]').val();
 var product_id     = select.eq(0).find('input[name=product_id]').val();
 var row_id         = select.eq(0).find('input[name=row_id]').val();
 var assay_id       = select.eq(0).find('input[name=assay_id]').val();


 var td1c   = td1.find('input[type=color]').val();
 var td1t   = td1.find('input[type=text]').val();

 var tc1    = td1.find('#tc1').val();
 var unit1  = td1.find('input[type=hidden]').val();
 var desc1  = td1.find('#desc1').val();
 var img1  = td1.find('#img1').val();
 
  
 var td2c = td2.find('input[type=color]').val();
 var td2t = td2.find('input[type=text]').val();
 var tc2  = td2.find('#tc2').val();
 var unit2  = td2.find('input[type=hidden]').val();
 var desc2  = td2.find('#desc2').val();
  var img2  = td2.find('#img2').val();

 var td3c = td3.find('input[type=color]').val();
 var td3t = td3.find('input[type=text]').val();
 var tc3  = td3.find('#tc3').val();
 var unit3  = td3.find('input[type=hidden]').val();
 var desc3  = td3.find('#desc3').val();
  var img3  = td3.find('#img3').val();

 var td4c = td4.find('input[type=color]').val();
 var td4t = td4.find('input[type=text]').val();
 var tc4  = td4.find('#tc4').val();
 var unit4  = td4.find('input[type=hidden]').val();
 var desc4  = td4.find('#desc4').val();
  var img4  = td4.find('#img4').val();

 var td5c = td5.find('input[type=color]').val();
 var td5t = td5.find('input[type=text]').val();
 var tc5  = td5.find('#tc5').val();
 var unit5  = td5.find('input[type=hidden]').val();
 var desc5  = td5.find('#desc5').val();
  var img5  = td5.find('#img5').val();

 var td6c = td6.find('input[type=color]').val();
 var td6t = td6.find('input[type=text]').val();
 var tc6  = td6.find('#tc6').val();
var unit6  = td6.find('input[type=hidden]').val();
var desc6  = td6.find('#desc6').val();
  var img6  = td6.find('#img6').val();

 var td7c = td7.find('input[type=color]').val();
 var td7t = td7.find('input[type=text]').val();
 var tc7  = td7.find('#tc7').val();
var unit7  = td7.find('input[type=hidden]').val();
var desc7  = td7.find('#desc7').val();
  var img7  = td7.find('#img7').val();
 if(img1 != null && desc1 != null || img2 != null && desc2 != null || img3 != null && desc3 != null || img4 != null && desc4 != null || img5 != null && desc5 != null || img6 != null && desc6 != null || img7 != null && desc7 != null)
 {
  alert('Please Choose Only One Dropdown');
  window.location.reload();
  return;
 }
  
   color_data = [{'c1':td1c,'v1':td1t,'tc1':tc1,'unit1':unit1,'description':desc1,'img':img1},{'c2':td2c,'v2':td2t,'tc2':tc2,'unit2':unit2,'description':desc2,'img':img2},{'c3':td3c,'v3':td3t,'tc3':tc3,'unit3':unit3,'description':desc3,'img':img3},{'c4':td4c,'v4':td4t,'tc4':tc4,'unit4':unit4,'description':desc4,'img':img4},{'c5':td5c,'v5':td5t,'tc5':tc5,'unit5':unit5,'description':desc5,'img':img5},{'c6':td6c,'v6':td6t,'tc6':tc6,'unit6':unit6,'description':desc6,'img':img6},{'c7':td7c,'v7':td7t,'tc7':tc7,'unit7':unit7,'description':desc7,'img':img7}];
   data={'_token':"{{ csrf_token()}}",'color_data':color_data, 'product_id':product_id,'assay_id':assay_id,'row_id':row_id};
   var url="{{url('updatechartbyajax')}}"; // the script where you handle the form input.

    $.ajax({
           type: "POST",
           url: url,
           data:data, // serializes the form's elements.
           success: function(data)
           {
               alert('Test Name, Color And Value Updated Successfully');
               window.location.reload();
           }
         });
 

 


});


</script>
<script src="{{asset('/js/validator.min.js')}}"></script>
<script src="{{asset('/js/bootstrap-select.js')}}"></script>
<script type="text/javascript">
  
</script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <script src="{{asset('/js/mousewheel.js')}}"></script>
    <script>
    $(document).ready(function() {
$('.table-responsive').mousewheel(function(e, delta) {
this.scrollLeft -= (delta * 350);
e.preventDefault();
});
});

</script>
<style type="text/css">
  select.selectpicker { display:none; /* Prevent FOUC */}
.media-object {
    height: 30px;
    width: 30px;
}
/*.bootstrap-select > .btn {
    width: 35%;
    padding-right: 25px;
}*/
.table_formate
{
  width: 60px !important;
  margin-left: 8px;
  height: 28px !important;
  margin-bottom: 8px;
}


/*------------------------------*/

</style>
@endsection