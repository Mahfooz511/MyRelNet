@extends('forms')

@section('form-panel-heading')
	Set Root Member in the family {{ $familyarray[key($familyarray)] }}
@endsection

@section('formcontent')				
	@if($members_count == 0)
		<div>
			<p>There is no member in family {{ $familyarray[key($familyarray)] }}</p>
			<a href={{ url("family/$famid/person/add")}}><div class="btn btn-primary " role="group" aria-label="Add-Person">Add Person</div></a>
		</div>
	@else
	<div class="alert alert-info " role="alert" style="width:60%;">
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  		<strong>Root: </strong> Top member in certain(and default) graph layout.
	</div>


		{!! Form::open(['url' => 'member/setroot', 'id' => 'setroot']) !!}
			<input name="family_id" type="hidden" value={{ key($familyarray) }}>
			<div class="form-group">
				{!! Form::select('memberlist', $members, 'default', array('id' => 'memberlist')) !!} 
			</div>
			<div class="relativeinfo">
					
				{{--@include('memberinfo')--}}
				
			</div>

			<div class="form-group">
				{!! Form::submit("Set" , ['class' => 'btn btn-primary form-control']) !!}
			</div>
		{!! Form::close() !!}
	@endif
			
<script>var famid = {{ $famid }} ;</script>

@endsection

			



