<div class="memberinfobox2">
	<div class="row" >
		@if(isset($firstpersondata) && $firstpersondata->image != null)
		<div class="col-md-2 membox" id="imagerow">			
			<img src={{ asset('/img/')."/".$firstpersondata->id."f".$firstpersondata->family_id.".".$firstpersondata->image}}>			
		</div>
		@endif
		<div class="col-md-10 membox">
			<div class="row" id="memboxname">
				@if(isset($firstpersondata))
					{{$firstpersondata->name}}
				@endif
			</div>
			<div class="row" id="memboxloc">
				@if(isset($firstpersondata->city) && $firstpersondata->city != "")
					From {{$firstpersondata->city}},
				@endif
				@if(isset($info))
				 	{{$info->relative}}'s {{$info->relation}}
				@endif
			</div>			
		</div>
	</div>			
</div>

