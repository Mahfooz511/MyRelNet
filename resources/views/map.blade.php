@extends('master-new')

@section('headerfiles')
	<script src="https://maps.googleapis.com/maps/api/js"></script>
  	<script src="{{ asset('/js/maps.js') }}"></script>
  	<script src="{{ asset('/js/oms.min.js') }}"></script>
@endsection

@section('content')

<div class="container no-sidepadding " style="height:100%;width:100%;margin: 0;">
	<div class="row no-sidepadding "  style="height:100%;margin: 0;"> 
	    <div class="col-md-2" style="height:100%; margin: 0; background-color:grey;"> 
	 		   

	    </div>
	    <div class="col-md-10" style="height:100%;margin: 0;padding: 0;"> 
	 		<div id="map-canvas" style="width:100%;height:100%;margin: 0;"></div>
			
		
	    </div>
	</div>  
</div>

@endsection

@section('data')
<script>
	var membersdata = "{{ $persons }}" ;
</script>
@endsection

