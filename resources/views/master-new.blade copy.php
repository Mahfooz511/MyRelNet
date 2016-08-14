<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="mahfooz">

    <title>RelNet</title>
    <!-- css -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href="{{ asset('/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/font-awesome/css/nivo-lightbox.css') }}" rel="stylesheet" />
	<link href="{{ asset('/css/font-awesome/css/nivo-lightbox-theme/default/default.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/css/animations.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/color/default.css') }}" rel="stylesheet">

</head>

<body>
    <!--  Top Navigation -->
    <div id="navigation">
		<nav class="navbar navbar-custom" role="navigation">
		    <div class="container">
		        <div class="row">
		            <div class="col-md-2">
		                <div class="site-logo">
		                    <a href="index.html" class="brand">Relnet</a>
		                </div>
		            </div>
		            <div class="col-md-10">
		                <!-- Brand and toggle get grouped for better mobile display -->
		                <div class="navbar-header">
		                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
		                    <i class="fa fa-bars"></i>
		                    </button>
		                </div>
		                <!-- Collect the nav links, forms, and other content for toggling -->
		                <div class="collapse navbar-collapse" id="menu">
		                    <ul class="nav navbar-nav navbar-right marginbot-20">
		                        <li class="active"><a href="{{ url('/home') }}">Home</a></li>
		                        <li><a href="#">Lang</a></li>
		                        <li><a href="{{ url('/auth/logout') }}">Log out</a></li>
		                    </ul>
		                </div>
		                <!-- /.Navbar-collapse -->
		            </div>
		        </div>
		    </div>
		    <!-- /.container -->
		</nav>
	</div> 
    <!-- /Top Navigation -->  

	<div class="container-fluid">
        <div class="row row-offcanvas row-offcanvas-left">
            <!-- sidebar -->
            <div class="col-md-2 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <ul class="nav">
                    <li class="active"><a class="" href="#">Home</a>
                    </li>
                    <li><a class="" href="#">Who Am I</a></li>
                    <li><a class="" href="#">Who is Vincent Henin</a></li>
                    <li><a class="" href="#">Why Vhenin</a></li>
                </ul>
            </div>
            
            <!-- main area -->
            <div class="col-md-10 col-sm-9">
                 @yield('content')
    				
  			</div>
		</div>
	</div>

	
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<ul class="footer-menu ">
						<li><a href="#">Home</a></li>
					</ul>
				</div>
				<div class="col-md-6 text-right">
					<p>&copy;Copyright 2015 RelNet | All Rights Reserved</p>
				</div>
			</div>	
		</div>
	</footer>

	

  

    <!-- Core JavaScript Files -->
    <script src="js/jquery.min.js"></script>	 
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
    <script src="js/jquery.easing.min.js"></script>	
	<script src="js/jquery.scrollTo.js"></script>
	<script src="js/jquery.appear.js"></script>
	<script src="js/stellar.js"></script>
	<script src="js/nivo-lightbox.min.js"></script>
	
    <script src="js/custom.js"></script>
	<script src="js/css3-animate-it.js"></script>

</body>

</html>
