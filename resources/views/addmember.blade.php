@extends('forms')

@section('form-panel-heading')
	Add Member in the family {{ $familyarray[key($familyarray)] }}
@endsection	

@section('formcontent')

	{!! Form::open(['url' => 'member/store', 'id' => 'member', 'files'=>true]) !!}
	
		<input name="family_id" type="hidden" value={{ key($familyarray) }}>
		<input name="firstmember" type="hidden" value={{ $firstmember }}>
		
		<?php
			if(! isset($relation)) $relation = null ;
			$addform = True;
			$editform = False ;
			$default_name = null ;
			$default_nickname = null ;
			$default_gender = null ;
			$default_siblingno = null ;
			$default_city = null ;
			$default_state = null ;
			$default_country = null ;
			$default_deadoralive_dead = True ;
			$default_deadoralive_alive = False ;
			$default_description = null ;
			$members_count = 0 ;
			$relative = null ;
			$default_gender_male = True ;
			$default_gender_female = False ;
		?>

		@include ('personform',['submitButtonText' => 'Add Member'])

	
	{!! Form::close() !!}


@endsection

@section('data')
	<script>
		var locationdata = "{{ isset($location)?$location:null  }}" ;
		var addform = true ;
	</script>
@endsection

			



