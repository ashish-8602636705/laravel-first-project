<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>HealthApp Admin</title>

        <!-- Bootstrap Core CSS -->
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="{{asset('/css/metisMenu.min.css')}}" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{asset('/css/startmin.css')}}" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="{{asset('/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

<body>

 <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <!-- left column -->
          <div class="col-md-4"></div>
          <div class="col-md-4 ">
            <!-- general form elements -->

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title" style="padding-left: 125px;padding-top: 50px;padding-bottom: 10px" >Reset Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <div class="thumbnail">
                <center>
                                @if(session('success'))
                                  <div class="alert alert-success alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>{{ session('success') }}</strong>
                                  </div>
                                                                
                                
                                @endif
                            </center>
              <form role="form" style="padding: 20px" action="{{url('password_reset')}}" method="post">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <input type="hidden" name="email" value="{{$email}}">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Password</label>
                    <input type="password" required="true" class="form-control" name="password">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Confirm Password</label>
                    <input type="password" required="true" class="form-control" name="confirm_password" >
                  </div>
               
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            </div>
            <!-- /.card -->

       
            <!-- /.card -->

            <!-- /.card -->

            <!-- Input addon -->
          
            <!-- /.card -->

          </div>
          
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>
</body>
</html>