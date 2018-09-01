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

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Please Sign In</h3>
                        </div>
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
                        <div class="panel-body">
                            <form data-toggle="validator" role="form" method="post" action="{{route('login')}}" id="form">
                                <fieldset>
                                    <div class="form-group">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input class="form-control" placeholder="Username" required="true" name="username" type="text" autofocus>
                                        <div class="help-block with-errors "></div>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" required="true" name="password" type="password" value="">
                                        <div class="help-block with-errors "></div>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button type="submit" href="index.html" class="btn btn-lg btn-success btn-block">Login</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="{{asset('/js/jquery.min.js')}}"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{asset('/js/metisMenu.min.js')}}"></script>

        <!-- Custom Theme JavaScript -->
        <script src="{{asset('/js/startmin.js')}}"></script>

        <script src="{{asset('/js/validator.min.js')}}"></script>
    </body>
</html> 