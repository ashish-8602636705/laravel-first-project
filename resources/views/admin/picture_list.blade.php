@extends('admin.layout.layout')
@section('page')       
<div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Picture List</h1>
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
                                Add new picture here
                               </div> 
                                <div class="col-sm-2"> 
                                    <a href="{{url('add_picture')}}" class="btn btn-success btn-md">Add Picture</a>
                               </div> 
                              </div>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                @if (session('success'))
                                <div class="alert alert-success">
                                {{ session('success') }}
                                </div>
                                @endif
                                @if (session('error'))
                                <div class="alert alert-danger">
                                {{ session('error') }}
                                </div>
                                @endif
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Category Name</th>
                                                <th>Picture Name</th>
                                                <th>Image</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @forelse ($picture_record as $cat)
                                            <tr class="odd gradeX">
                                                <td>{{$cat->name}}</td>
                                                <td>{{$cat->pic_name}}</td>
                                                <td>@if (file_exists( public_path()."/images/images/".$cat->image))  
                                                        <img src="{{asset('/images/images/'.$cat->image)}}" height="50px" width="50px">
                                                    @else
                                                        <img src="{{asset('/images/no_image.png')}}" height="100px" width="100px">
                                                    @endif
                                                </td>
                                                <td class="center"><a href="{{url('edit_picture/'.$cat->id)}}"><img src="{{asset('/images/feedbin-icon.png')}}" ></a></td>
                                                <td class="center"><a href="#" onclick="var i = confirm('Do you really want to delete record.'); if(i=='1'){ deleteRecord({{ $cat->id }})}"><img src="{{asset('/images/delete-512.png')}}"></a></td>
                                            </tr>
                                            @empty
                                            <p>No users</p>
                                            @endforelse
                                        </tbody>
                                    </table>
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
            $(document).ready(function() {
           /*    var table;   
    
             $('#dataTables-example').dataTable({
                "bPaginate": true,
                "bLengthChange": true,
                "sPaginationType": "full_numbers",
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": false,
                "processing": false,
                "serverSide": true,
                "stateSave": false,
                "pageLength": 10,
                "ajax": "{{ url('getDynamiccategoryRecord') }}",
                    "aoColumns":[
                             {"bSortable": false},
                             {"bSortable": false},
                             {"bSortable": false},
                             {"bSortable": false}
                    ],
                    oLanguage: {
                    oPaginate: {
                      sFirst: "First",
                      sPrevious: "Previous",
                      sNext: "Next",
                      sLast: "Last"
                    }
                },
    
            });*/
                $('#dataTables-example').DataTable({
                        responsive: true
                });
    });
            
     function deleteRecord(id){
         var id = id;
         var csrf_id = $("#csrf_id").val(); 
         $.ajax({
              "headers":{

                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                  },
              'url' : '{{ url("deletePictureRecord") }}',
              'data' :{id:id,_token:csrf_id},
              'type' :'POST',
              'success' : function(response){
                    if(response=='1'){
                        location.reload();
                    }
               }
          });
     }       
  </script>
@endsection
  

