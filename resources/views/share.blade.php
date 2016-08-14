@extends('forms')

@section('form-panel-heading')
	Share Family With Others
@endsection

@section('formcontent')
	@include ('errors.formerrors')
{!! Form::open(['url' => "family/share", 'id' => 'share']) !!}
	<input name="family_id" type="hidden" value={{ $famid }}>
	
	<fieldset>
		<legend>Access Type</legend>
	<div class="form-group">
		{!! Form::radio('access','view', true,['id' => 'view' ]) !!} 
		<label for="view"><span></span>View</label>
		{!! Form::radio('access','edit',false,['id' => 'edit' ]) !!} 
		<label for="edit"><span></span>Edit</label>
	</div>
	</fieldset>

	<fieldset>
		<legend>Share Type</legend>
		<div class="form-group">
			{!! Form::radio('sharetype','Single', true, ['id' => 'single' ]) !!} 
			<label for="single"><span></span>Single</label>
			{!! Form::radio('sharetype','Group', false, ['id' => 'group' ]) !!} 
			<label for="group"><span></span>Group</label>
		</div>
		<div class="form-group">
			<fieldset id="validityfieldset" style="display: none;">  
				<legend>Validity</legend>  
				{!! Form::radio('validity','1', true, ['id' => 'validity1' ]) !!}
				<label for="validity1"><span></span>One Week</label>
				{!! Form::radio('validity','2', false,['id' => 'validity2' ]) !!}
				<label for="validity2"><span></span>Two Week</label>
				{!! Form::radio('validity','3', false,['id' => 'validity3' ]) !!}
				<label for="validity3"><span></span>One Month</label>
			</fieldset>  
		</div>
	</fieldset>
	
	</br>

	<div class="form-group">
		{!! Form::submit("Share" , ['class' => 'btn btn-primary form-control']) !!}
	</div>
{!! Form::close() !!}
				
	

@endsection

			



