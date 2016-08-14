@extends('forms')

@section('form-panel-heading')
	Delete Member in the family {{ $familyarray[key($familyarray)] }}
@endsection

@section('formcontent')				
	@if($members_count == 0)
		<div>
			<p>There is no person to delete in family {{ $familyarray[key($familyarray)] }}</p>
			<a href={{ url("family/$famid/person/add")}}><div class="btn btn-primary " role="group" aria-label="Add-Person">Add Person</div></a>

		</div>
	@else
		{!! Form::open(['url' => 'member/remove', 'id' => 'memberdelete']) !!}
			<input name="family_id" type="hidden" value={{ key($familyarray) }}>
			<div class="form-group">
				{!! Form::select('delmemberlist', $members, 'default', array('id' => 'delmemberlist')) !!} 
			</div>
			<div class="relativeinfo">
					
			</div>
			<div class="deleteinfo">
				
			</div>
			</br>						

			<div class="form-group">
				{!! Form::submit("Delete" , ['class' => 'btn btn-primary form-control']) !!}
			</div>
		{!! Form::close() !!}
	@endif
			
<script>var famid = {{ $famid }} ;</script>

@endsection

			



