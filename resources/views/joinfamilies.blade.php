@extends('forms')

@section('form-panel-heading')
	Join Families
@endsection

@section('formcontent')
	{!! Form::open(['url' => 'family/join', 'method' => 'POST']) !!}
		<div class="container">
		  <div class="row">
		    <div class="col-md-4">
		      <div class="form-group">
		        <label for="firstfamily">From Family</label></br>
		        {!! Form::select('firstfamily', array(null => 'Choose Family...') + $families,'default', array('id' => 'firstfamily')) !!} 
		      </div>
		    </div>
		    <div class="col-md-4">
		    	<div class="form-group">
			      <label for="firstfamilymember">Person</label></br>
			      {!! Form::select('firstfamilymember', array("" => 'Choose Member...') ,'default', array('id' => 'firstmember')) !!}
			  	</div>
		    </div><div class="col-md-4">      
		    </div>
		  </div>
		  <div class="row">
		    <div class="col-md-12">
		      <div class="form-group">
		        <label for="relation">is</label></br>
		        {!! Form::select('relation', array(null => 'Relation...') + $relations,'default', array('id' => 'relation')) !!}  
		      </div>
		    </div>
		  </div>
		  <div class="row">
		    <div class="col-md-4">
		      <div class="form-group">
		        <label for="secondfamily">of Family</label></br>
		        {!! Form::select('secondfamily', array(null => 'Choose Family...') + $families,'default', array('id' => 'secondfamily')) !!}
		      </div>
		    </div>
		    <div class="col-md-4">
		      <div class="form-group">
		      	<label for="secondfamilymember">Person</label></br>
		        {!! Form::select('secondfamilymember', array("" => 'Choose Member...'),'default',array('id' => 'secondmember')) !!} 
		      </div>
		    </div>
		  </div>
		  <div class="row">
		    <div class="col-md-12">
		       <div class="form-group">
		      {!! Form::radio('option',1, true) !!} Keep original familes. </br>
		      {!! Form::radio('option',2      ) !!} Just keep one new family and remove old familes.
		    </div>
		    </div>
		  </div>
		  <div class="row">
		     <div class="form-group">
		      {!! Form::submit('Join ' , ['class' => 'btn btn-primary form-control']) !!}
		    </div>  
		  </div>
		</div>	

	{!! Form::close() !!}
@endsection

			



