<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="mahfooz">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>RelNet</title>
    <base href="{{ Request::url() }}">	
    <!-- css -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	
	<!--script src="{{ asset('/js/cytoscape/build/cytoscape.min.js') }}"></script-->
	<script src="{{ asset('/js/relnettools.js') }}"></script>
	<!--script src="{{ asset('/js/relmap.js') }}"></script-->

    <!-- <link href="{{ asset('/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<!--link href="{{ asset('/css/font-awesome/css/nivo-lightbox.css') }}" rel="stylesheet" /-->
	<!--link href="{{ asset('/css/css/nivo-lightbox-theme/default/default.css') }}" rel="stylesheet" type="text/css" /-->
	<!--link href="{{ asset('/css/animations.css') }}" rel="stylesheet" /-->
    
	<link href="{{ asset('/css/color/default.css') }}" rel="stylesheet">  
  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<!--script src="//code.jquery.com/jquery-1.10.2.js"></script-->
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link href="{{ asset('/css/style.css') }}" rel="stylesheet">

  	<script src="{{ asset('/js/jquerycombo.ui.js') }}"></script>
  	
  	
	@yield('headerfiles')  

</head>

<body>
    <!--  Top Navigation -->    		
    <div id="navigation">
		<nav class="navbar navbar-custom" role="navigation">
		    <div class="container">
		        <div class="row">
		            <div class="col-md-2">
		                <div class="site-logo">
		                    <a href="home" class="brand">Relnet</a>

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
		                        <li><a href="#" style='pointer-events: none;'>Notifications</a></li>
		                        <li>
		                        	<dl id="sample" class="dropdown">
			        					<dt><a href="#"><span id="selected"> {{ $userpref["lang"] }}</span><span class="caret"></span></a></dt>
		        						<dd>
		            					<ul>
		                					<li><a href="#">English</a></li>
		                					<li><a href="#">Hindi</a></li>
		                					<li><a href="#">Urdu</a></li>
		            					</ul>
		        						</dd>
   					 				</dl>   					 			 
   					 			</li>
		                        <li><a href="#" style='pointer-events: none;'>Help</a></li>
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
	
	<div class="container-fluid no-sidepadding" id="maincontentarea" >
        <div class="row " style="height:100%;">
            <!-- sidebar -->
             @include('sidemenu') 
	
            <!-- main area, all forms and graph -->
            <div  class="col-md-12	 col-sm-9 no-sidepadding" id="contentarea" style="height:100%;">
                 @yield('content')   
  		 	</div>
		
		</div>
	</div>

    <div class="lightbox forms" id="lightbox_m" >
        <div class="panel panel-default form-panel">
           <div class="panel-heading form-panel-heading"><span id="lightbox_title"></span><a href=""><span class="glyphicon glyphicon-remove"></span></a></div>
            <div class="panelcontent"> 
            	<div class="row">
            		<div class='col-md-12'> 
	            		<div class="form-group">
	                   		<div id="lightbox_content_m" class="lightbox_content"></div> 
	                   	</div>
                   </div>
               </div>
               <div class="row">
                   <div class='col-md-12'> 
                   		<div class="form-group">
	                   		<div id="lightbox_content_m2" class="lightbox_content"></div> 
	                   	</div>
                   </div>
                </div>
                <div class="row"></div>
                <div class="row">
                    <div class="form-group">
                        <input class="btn btn-primary form-control" id="submit" type="submit" value="Go..." style="margin-top:50px;">
                    </div>
                </div>
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

	@yield('data')

</body>

</html>
