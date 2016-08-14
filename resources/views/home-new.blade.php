@extends('master-new')

@section('content')
<?php   $count = 0  ;
  $lines = count($families)/4 + 1 ;
?>
<div class="family-home" style="margin-left: 100px; min-height: 425px;">
</br>
	<div class="row sidemenumargin" id="boxhome"> 
		<div class="col-md-12 "> 
		    <?  // php   $count = 0  ;
			  //  $lines = count($families)/4 + 1 ;
			?> 
			@for ($i = 1, $cn = 0; $i <= $lines; $i++)
    			<div class="row">
    				@for ($j = 1; $j <= 4; $j++)
    					<div class="col-lg-3 col-md-3"> {{--- Some problem here--}}
    						<?php  $count++ ; ?>
    						@if ($count >= (count($families) + 1 ) )
    							<div class="addfamilybox " >
					    			<a href={{ url("/family/create") }} style="align-items: center;justify-content: center;">
                                        <span class="glyphicon glyphicon-plus">                                            
                                        </span></br>ADD FAMILY
                                    </a>
								</div>
								<?php echo "</div>" ;break ; ?>
    						@else
    							<div class="familybox ">
    								
    								<a href={{ url("/family/".$families[$cn]->id) }}> {{ $families[$cn++]->name  }} </a>
    							</div>
    						@endif
    					</div>
    				@endfor
    			</div>
			@endfor	
		</div>
	</div>
</div>
@endsection
