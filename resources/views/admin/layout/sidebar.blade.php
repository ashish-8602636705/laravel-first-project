<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <a class="navbar-brand" href="">Admin</a>
                </div>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

               

                <ul class="nav navbar-right navbar-top-links">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> @if (session('username')){{ session('username') }}@endif<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="{{url('profile')}}"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li>
                            <!-- <li><a href="{{url('change_password')}}"><i class="fa fa-gear fa-fw"></i> Settings</a>
                            </li> -->
                            <li class="divider"></li>
                            <li><a href="{{url('logout')}}"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            
                            <li>
                                <a href="{{url('dashboard')}}" ><i class="fa fa-home fa-fw"></i> Home</a>
                            </li>
                            <li>
                                <a href="{{url('users')}}" ><i class="fa fa-users fa-fw"></i> Users</a>
                            </li>
                            <li>
                                <a href="{{url('allcategories')}}"><i class="fa fa-sitemap fa-fw"></i> Brand</a>
                            </li>
                            <li>
                                <a href="{{url('showproduct')}}"><i class="fa fa-file-image-o fa-fw"></i> Products</a>
                                
                            </li>
                            <li>
                                <a href="{{url('Showassay')}}"><i class="fa fa-table" aria-hidden="true"></i>  Assay</a>
                                
                            </li>
                             <li>
                                <a href="{{url('showillness')}}"><i class="fa fa-table" aria-hidden="true"></i> Diagnosed Illness</a>
                                
                            </li>
                            <li>
                                <a href="{{url('loadresult')}}"><i class="fa fa-book" aria-hidden="true"></i> User Result</a>
                                
                            </li>
                            <li>
                                <a href="{{url('loadPopularProduct')}}"><i class="fa fa-bullhorn" aria-hidden="true"></i> Popular Product</a>
                                
                            </li>
                            <li>
                                <a href="{{url('loadaddimage')}}"><i class="fa fa-image" aria-hidden="true"></i> Add Image</a>
                                
                            </li>
                            <li>
                                <a href="{{url('loadshorttext')}}"><i class="fa fa-text-width" aria-hidden="true"></i>  Add Short Text</a>
                                
                            </li>
                            <li>
                                <a href="{{url('t&c')}}"><i class="fa fa-handshake-o" aria-hidden="true"></i> Terms & Conditions</a>
                                
                            </li>
                       </ul>
                    </div>
                </div>
            </nav>
