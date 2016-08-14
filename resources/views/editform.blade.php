@extends('forms')

@section('form-panel-heading')
	Edit Member in the family {{ $familyarray[key($familyarray)] }}
@endsection

@section('formcontent')
<?php $editform = True ; ?>
	@if($members_count == 0)
		<div>
			<p>There is no person to edit in family {{ $familyarray[key($familyarray)] }}</p>
			<a href={{ url("family/$famid/person/add")}}><div class="btn btn-primary " role="group" aria-label="Add-Person">Add Person</div></a>
		</div>
	@else
		{!! Form::open(['url' => "family/$famid/person/edit", 'id' => 'memberedit', 'files'=>true]) !!}
			
			<input name="family_id" type="hidden" value={{ key($familyarray) }}>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group" id="myid1">
						{!! Form::select('editmemberlist', $members,$firstpersondata->id, array('id' => 'editmemberlist'), $firstpersondata->id) !!} 
					</div>
				</div>
				<div class="col-md-4">
					@include('memberinfo')
				</div>
			</div>
			<hr>
		
			<?php
			$addform = False ;
			//$firstmember = False ;
			$default_name = $firstpersondata->name ;
			$default_nickname = $firstpersondata->nickname ;
			$default_siblingno = $firstpersondata->siblingno - 1 ;
			$default_city = $firstpersondata->city ;
			$default_state = $firstpersondata->state ;
			$default_country = $firstpersondata->country ;
			$default_deadoralive_dead = ($firstpersondata->deadoralive == "alive" );
			$default_deadoralive_alive = ($firstpersondata->deadoralive == "dead" );
			$default_gender_male = ( $firstpersondata->gender == 'Male' ) ;
			$default_gender_female = ( $firstpersondata->gender == 'Female' ) ;
			$default_description = $firstpersondata->description ;

			?>
			@include ('personform',['submitButtonText' => 'Update Member']) 

		{{-- SIBLING-NO DEBUG--}}
		{!! Form::close() !!}
	@endif
			
<script>
var famid = {{ $famid }} ;
//var edit = True ;
var locationdata = "{{ $location }}" ;
</script>

@endsection

			



