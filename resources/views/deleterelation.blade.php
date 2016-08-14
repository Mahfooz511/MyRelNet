@extends('forms')

@section('form-panel-heading')
	Delete extra relations 
@endsection

@section('formcontent')
	@if($count < 1)
		<div>
			<p>There are no extra(double) relations in this family.</p>
			<a href={{ url("family/$famid/relation/add")}}><div class="btn btn-primary " role="group" aria-label="Add-Relation">Add Relation</div></a>
		</div>
	@else	
		{!! Form::open(['url' => "family/$famid/relation/delete", 'id' => 'relationdelete']) !!}
			
			<input name="family_id" type="hidden" value={{ key($familyarray) }}>
			<div class="row" >
				<div class="col-md-8">
					<div class="form-group" id="myid1">
						{!! Form::select('relationlist', $relationarray, null, array('id' => 'relationlist'),null) !!} 
					</div>
				</div>
			</div>


			</br>
			<div class="form-group">				
				{!! Form::submit('Delete Relation' , ['class' => 'btn btn-primary']) !!}
			</div>	

		{!! Form::close() !!}
	@endif
			
@endsection

@section('data')
	<script>
		// var locationdata = "{{ isset($location)?$location:null  }}" ;
		// var addform = true ;
	</script>
@endsection


