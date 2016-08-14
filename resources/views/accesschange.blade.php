@extends('forms')

@section('form-panel-heading')
	Change Access for the family {{ $faminfo[$famid] }}
@endsection

@section('formcontent')
		{!! Form::open(['url' => "family/$famid/access", 'id' => 'access']) !!}
			
			<input name="family_id" type="hidden" value={{ $famid }}>
			<?php $accesscount = 0 ; ?>
			@foreach($famaccess as $access)
			<?php $accesscount++ ; ?>
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						{{$access->name}}
					</div>
					<div class="row">
						<span style="font-size:14px;">{{$access->email}}</span>
					</div>
				</div>
				<div class="col-md-6">					
					{{-- If user is owner then he can change owner, else he cant change owner. 
					owner will be changed through js, only if other user selected as owner --}}
					<!-- <div class="form-group"> -->
					@if($access->aceess_type != 'owner')
						@if($owner)
							{!! Form::select($access->id, array('owner' => 'owner', 'edit' => 'edit' , 'view' => 'view', 'noaccess' => 'NO ACCESS'), $access->aceess_type, array('id' => 'accesstype' . $accesscount)) !!} 
						@else
							{!! Form::select($access->id, array( 'edit' => 'edit' , 'view' => 'view', 'noaccess' => 'NO ACCESS'), $access->aceess_type, array('id' => 'accesstype' . $accesscount, 'class' => 'accessgroup')) !!} 
						@endif
					@else 
						{!! Form::select($access->id, array('owner' => 'owner', 'edit' => 'edit' , 'view' => 'view' ), $access->aceess_type, array('id' => 'accesstype' . $accesscount , 'disabled' => 'disabled' , 'style' => 'width:110px;', 'class' => 'accessgroup')) !!}       
					@endif
					<!-- </div>	 -->
				</div>
			</div>
			<hr>
			@endforeach
			
			<div class="form-group">
				{!! Form::submit("Update" , ['class' => 'btn btn-primary']) !!}
			</div>	
		{!! Form::close() !!}
			
@endsection

			



