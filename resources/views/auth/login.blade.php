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

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">
	
	<section class="hero" id="intro">
            <div class="container">
              <div class="row">
                <div class="col-md-7  text-center inner">
					<div class="animatedParent">
						<h1 class="animated fadeInDown">RELations NETwork</h1>
						<p class="animated fadeInUp">Keep track of relations.</p>
						<a id="introsignup" href="#contact" ><span>Sign Up</span> - It’s free and always will be.</a>
					</div>
					<div class="col-md-6 col-md-offset-3 text-center marginbot-20">
                  		<a href="#about" class="learn-more-btn btn-scroll">Learn more</a>
                	</div>
			    </div>
			    <div class="col-md-5  inner">
			    	<form  class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						
						<div class="row form-group">
							<div class="col-md-6 col-md-offset-4">
								<input type="email" class="form-control input-lg" id="email" name="email" placeholder="Enter email" required="required" value="{{ old('email') }}"/>
							</div>	
						</div>
						<div class="row form-group">
							<div class="col-md-6 col-md-offset-4">
								<input type="password" class="form-control input-lg" name="password" placeholder="Password">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Remember Me
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-skin btn-scroll">Login</button>
							</div>
						</div>
						<a class="btn btn-link col-md-offset-4" href="{{ url('/password/email') }}">Forgot Your Password?</a>
					</form>
			    </div>
              </div>	
              <div class="row">
               
              </div>
            </div>
    </section>
	
	
    <!-- Navigation -->
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
		                        <li class="active"><a href="#intro">Home</a></li>
		                        <li><a href="#about">About</a></li>
		                        <li><a href="#service">Features</a></li>
		                        <li><a href="#works">Demo</a></li>
		                        <li><a href="#contact">Sign up</a></li>
		                    </ul>
		                </div>
		                <!-- /.Navbar-collapse -->
		            </div>
		        </div>
		    </div>
		    <!-- /.container -->
		</nav>
	</div> 
    <!-- /Navigation -->  

	<!-- Section: about -->
    <section id="about" class="home-section color-dark bg-white">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="animatedParent">
					<div class="section-heading text-center animated bounceInDown">
					<h2 class="h-bold">About RelNet</h2>
					<div class="divider-header"></div>
					</div>
					</div>
				</div>
			</div>

		</div>

		<div class="container">

		
        <div class="row">
		
		
            <div class="col-lg-8 col-lg-offset-2 animatedParent">		
				<div class="text-center">
					<p>Even the stars stays in Family, known as Galaxy. 
					In today's time, in the sea of people, its very easy to loose track of your relations. 
					Busy life, geographical distances, and cultural mix makes it even tougher.
					</p>
					<p>RelNet, short for Relations Network, is an endeavour to provide a sophisticated tool to those people, who cares of relations, and cherish them. It is not the Facebook type social network platform where everyone is your 'Friend'. It is a collaborative tool, where you have a name of every relation.
					</p>
					<a href="#service" class="btn btn-skin btn-scroll">Features</a>	
				</div>
            </div>
		

        </div>		
		</div>

	</section>
	<!-- /Section: about -->
	
	
	<!-- Section: services -->
    <section id="service" class="home-section color-dark bg-gray">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div>
					<div class="section-heading text-center">
					<h2 class="h-bold">What You Can Do</h2>
					<div class="divider-header"></div>
					</div>
					</div>
				</div>
			</div>

		</div>

		<div class="text-center">
		<div class="container">

        <div class="row animatedParent">
            <div class="col-xs-6 col-sm-4 col-md-4">
				<div class="animated rotateInDownLeft">
                <div class="service-box">
					<div class="service-icon">
						<span class="fa fa-sitemap fa-2x"></span> 
					</div>
					<div class="service-desc">						
						<h5>Graphical Map</h5>
						<div class="divider-header"></div>
						<p>
						See the family in a connected graph, with name, pictures, and relations. Change the graph layout as you want.
						</p>
						<!-- <a href="#" class="btn btn-skin">Learn more</a> -->
					</div>
                </div>
				</div>
            </div>
			<div class="col-xs-6 col-sm-4 col-md-4">
				<div class="animated rotateInDownLeft slow">
                <div class="service-box">
					<div class="service-icon">
						<span class="fa  fa-share fa-2x"></span> 
					</div>
					<div class="service-desc">
						<h5>Collaborate and Share</h5>
						<div class="divider-header"></div>
						<p>
						Share family with other members and friends. Grow family with collaboration of authenticated members.
						</p>
						<!-- <a href="#" class="btn btn-skin">Learn more</a> -->
					</div>
                </div>
				</div>
            </div>
			<div class="col-xs-6 col-sm-4 col-md-4">
				<div class="animated rotateInDownLeft slower">
                <div class="service-box">
					<div class="service-icon">
						<span class="fa fa-search fa-2x"></span> 
					</div>
					<div class="service-desc">
						<h5>Find People and Relations</h5>
						<div class="divider-header"></div>
						<p>
						Find people and check how they are connected. Find relations between any two members, in multiple languages.
						</p>
						<!-- <a href="#" class="btn btn-skin">Learn more</a> -->
					</div>
                </div>
				</div>
            </div>

        </div>		
		</div>
		</div>
	</section>
	<!-- /Section: services -->
	

	<!-- Section: works -->
    <section id="works" class="home-section color-dark text-center bg-white">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div>
					<div class="animatedParent">
					<div class="section-heading text-center">
					<h2 class="h-bold animated bounceInDown">Showcase</h2>
					<div class="divider-header"></div>
					</div>
					</div>
					</div>
				</div>
			</div>

		</div>

		
		<div class="container">
            <div class="row animatedParent">
                <div class="col-sm-12 col-md-12 col-lg-12" >
                   
                    <div class="row gallery-item">
                        <div class="col-md-3 animated fadeInUp sub">
							<a href="">
								<img id="firstimg" src="img/works/relnetmf1.png" class="img-responsive" alt="img">
							</a>
						</div>

						<div class="col-md-3 animated fadeInUp slow sub">
							<a href="" >
								<img id="secondimg" src="img/works/relnetmf2.png" class="img-responsive" alt="img">
							</a>
						</div>
						<div class="col-md-3 animated fadeInUp slower sub">
							<a href="" >
								<img  id="thirdimg" src="img/works/relnetbk3.png" class="img-responsive" alt="img">
							</a>
						</div>
						<div class="col-md-3 animated fadeInUp sub">
							<a href="" >
								<img  id="fourthimg" src="img/works/relnetbk4.png" class="img-responsive" alt="img">
							</a>
						</div>
					</div> 
	
                </div>
            </div>	
		</div>
		<div id="divLargerImage">
			<video width="720"  autoplay>
  				<source src="" type="video/mp4">
			</video>
		</div>


		<div id="divOverlay"></div>

	</section>
	<!-- /Section: works -->

	<!-- Section: contact -->
    <section id="contact" class="home-section nopadd-bot color-dark bg-gray text-center">
		<div class="container marginbot-50">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="animatedParent">
					<div class="section-heading text-center">
					<h2 class="h-bold animated bounceInDown">Sign up</h2>
					<div class="divider-header"></div>
					</div>
					</div>
				</div>
			</div>

		</div>
		
		<div class="container">

			<div class="row marginbot-80">
				<div class="col-md-8 ">
					<form id="contact-form" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="row marginbot-20">
							<div class="col-md-6 xs-marginbot-20">
								<input type="text" class="form-control input-lg" id="name" name="name" placeholder="Enter name" required="required" value="{{ old('name') }}" />
							</div>
						</div>
						<div class="row marginbot-20">
							<div class="col-md-6">
								<input type="email" class="form-control input-lg" id="email" placeholder="Enter email" required="required"  name="email" value="{{ old('email') }}" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<input type="password" class="form-control input-lg" id="password" placeholder="Password" required="required" name="password" />
								</div>
								
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="password_confirmation" placeholder="Confirm Password" required="required">
								</div>
								
														
								<button type="submit" class="btn btn-skin btn-lg btn-block" id="btnSigunup">
									Sign Up</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-4 ">
					<div class="row ">
						<div class="col-md-6 text-left">
							<p><a href="fblogin">Join Using FB</a></p>
						</div>
					</div>
					<div class="row ">
						<div class="col-md-6 text-left">							
							<p>Join Using Google</p>
						</div>
					</div>
				</div>
				
			</div>	
			


		</div>
	</section>
	<!-- /Section: contact -->


	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<ul class="footer-menu">
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
<script>
	
$('a img').click(function () {
    var $img = $(this);
    $('#divLargerImage').show();
    $(this).add($('#divOverlay')).fadeIn();
    var myimg = $(this).attr('id') ;
    var myvidsrc = "";
    switch(myimg){
    	case "firstimg":
    		myvidsrc = "img/works/relnetmf1.mp4" ;
    		break ;
    	case "secondimg":
    		myvidsrc = "img/works/relnetmf2.mp4" ;
    		break ;
    	case "thirdimg":
    		myvidsrc = "img/works/relnetbk3.mp4" ;
    		break ;
    	case "fourthimg":
    		myvidsrc = "img/works/relnetbk4.mp4" ;
    		break ;
    }
    $('#divLargerImage video').attr("src",myvidsrc);

    $('#divLargerImage video').get(0).play();

});

$('#divLargerImage').add($('#divOverlay')).click(function () {
    $('#divLargerImage').add($('#divOverlay')).fadeOut(function () {
         $('#divLargerImage').hide();
         $('#divLargerImage video').get(0).pause()
    });
});

</script>

</html>
