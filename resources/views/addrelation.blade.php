@extends('forms')

@section('form-panel-heading')
	Add extra relations 
@endsection

@section('formcontent')
	@if($members_count <= 2)
		<div>
			<p>There are not enough members add extra relations in this family. </p>
			<a href={{ url("family/$famid/person/add")}}><div class="btn btn-primary " role="group" aria-label="Add-Person">Add Person</div></a>
		</div>
	@else
		{!! Form::open(['url' => "family/$famid/relation/add", 'id' => 'relationadd']) !!}
			
			<input name="family_id" type="hidden" value={{ key($familyarray) }}>
			<div class="row list1" >
				<div class="col-md-4">
					<div class="form-group" id="myid1">
						{!! Form::select('relative_id', $membersarray, $firstpersondata->id, array('id' => 'relative_id'), $firstpersondata->id) !!} 
					</div>
				</div>
				<div class="col-md-4">
					@include('memberinfo')
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">					
					<div class="form-group">
						{!! Form::label('relation', 'is') !!}</br>
						{!! Form::select('relation', $relationarray, null ,array('id' => 'relation', 'class' => 'form-control  ')) !!} 
					</div>
				</div>
			</div>

			<div class="row list2">
				<div class="col-md-4">				
					<div class="form-group">
						{!! Form::label('relative_id2', 'of') !!}</br>
						{!! Form::select('relative_id2', $membersarray, $firstpersondata->id, array('id' => 'relative_id2', 'class' => ' form-control ')) !!} 	
					</div>
				</div>
				</br>
				<div class="col-md-4">
					@include('memberinfo2')
				</div>
			</div>

			</br>
			<div class="form-group">				
				{!! Form::submit('Add Relation' , ['class' => 'btn btn-primary']) !!}
			</div>	

		{!! Form::close() !!}
	@endif
			
@endsection

@section('data')
	<script>
		// var locationdata = "{{ isset($location)?$location:null  }}" ;
		var addform = true ;
		var addrelation = true ;
	</script>
@endsection


