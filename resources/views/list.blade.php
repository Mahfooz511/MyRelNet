
@extends('master-new')


@section('content')
<div class="container forms" id="forms">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="list" style="background-color:grey;">				
				@foreach ($members as $member)
				<div class="row listitem">
					<div class="col-md-2">
						@if($member->image != null)
			    			<img src={{ asset('/img/')."/".$member->image}} style="width:80px;height:80px;">			
			    		@elseif($member->gender == 'Male')
			    			<img src={{ asset('/img/')."/man.png"}} style="width:80px;height:80px;">			
			    		@else
			    			<img src={{ asset('/img/')."/woman.png"}} style="width:80px;height:80px;">			
			    		@endif
			    	</div>
			    	<div class="col-md-10">
				    	<div class="row" style="margin-top:15px;">
				    		<span class="listitemname"><a href={{ url("family/$famid")}}?p={{$member->id}} title="Find in Graph">   {{ $member->name }} </a></span>			    
				    	</div>
				    	<div class="row" style="margin-top:15px;">
				    		@if($member->city != null || $member->state != null || $member->country != null)
				    			<span class="listitemname"> 
				    			@if($member->deadoralive == "dead")
				    				Lived in
				    			@else
				    				Lives in 
				    			@endif
				    			{{ $member->city}}
				    			@if($member->city != null && $member->state != null)
				    				, 
				    			@endif
				    			{{$member->state}}
				    			@if($member->state != null && $member->country != null)
				    				, 
				    			@endif
				    			{{$member->country}} </span>			    	
				    		@endif
				    	</div>
				    </div>
			    </div>
				@endforeach
			</div>
		</div>
	</div>
</div>

@endsection


			



