						{{-- if first member of family then no relation exist  --}}
						@if( (isset($firstmember) &&  !$firstmember) || ( $editform && ($members_count != 1 && $relative != null)) )  
							<div class="row" id="check">
								<div class="col-md-8">
									<div class="row">
										<fieldset>
											<legend>This Person is</legend>
											<div class="col-md-6">					
												<div class="form-group">
													{!! Form::select('relation', $relationarray, $relation ,array('id' => 'relation', 'class' => 'form-control')) !!} 
												</div>
											</div>

											<div class="col-md-6">				
												<div class="form-group">
													<label for="combobox" id="combolabel">Of</label>
													{!! Form::select('relative_id', $relativearray, $relative, array('id' => 'relative_id', 'class' => ' form-control ')) !!} 
												{{--	{!! Form::select('relative_id', $relativearray,$relative, array('id' => 'editrelativememberlist', 'class' => ' form-control ui-widget')) !!} --}}
													<div class="relativeinfo">   
													</div>
												</div>
											</div>
											
										</fieldset>
									</div>					
								</div>
								<div class="col-md-4">										
									@if(! $editform)
										@include('memberinfo')
									@endif
								</div>
							</div>
						@endif

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									
									{!! Form::label('name', 'Name:') !!}
									{!! Form::text('name',$default_name, ['class' => 'form-control'] ) !!}
							 	</div>
						 	</div>

						 	<div class="col-md-8">
								<div class="form-group"> 
									{!! Form::label('nickname', 'Known As:') !!}
									{!! Form::text('nickname',$default_nickname, ['class' => 'form-control'] ) !!}
								</div>
							</div>
						</div>

						{{-- Gender to select only when first meember to edit  --}}
						@if( ((isset($firstmember) && $firstmember)) || ( $editform && ($members_count == 1 || $relative == null) ) )  
							<div class="form-group radio-toolbar">
								{!! Form::radio('gender','Male', $default_gender_male, ['id' => 'Male' ]) !!} 
								{!! Form::label('Male', 'Male') !!}
								{!! Form::radio('gender','Female', $default_gender_female, ['id' => 'Female' ]) !!} 
								{!! Form::label('Female', 'Female') !!}
							</div>
						@endif

						
					

						@if(isset($editform) && $editform) 
							<div class="form-group">
								{!! Form::label('imgremove', 'Remove Image:') !!}
								{!! Form::checkbox('imgremove' ) !!}
							
								@if($firstpersondata->image != null)
									<span class="editformimg">
										<img src="{{ url('/img/'. $firstpersondata->id . 'f' . key($familyarray) . '.' . $firstpersondata->image) }}" class="img-thumbnail" alt="Member Img" width="60" height="46">	
									</span>
								@endif
							</div>
						@endif

						<div class="form-group" id="photo">
						{{--	{!! Form::label('image', 'Image:') !!} --}}
							{!! Form::file('image', ["class" => "filestyle" , "data-input" => "false" , "data-buttonText" => "Photo Upload", "data-iconName" => "glyphicon glyphicon-camera"] ) !!}
						</div>


						<div class="row">
						   <div class="col-md-8">
						      <fieldset id="location">
						         <legend>Location</legend>
						         <div class="row">
						            <div class="col-md-6">
						               <div class="form-group ">
						                  {!! Form::label('city', 'City:') !!}
						                  {!! Form::text('city',$default_city, ['class' => 'form-control ui-widget'] ) !!}
						               </div>
						               
						            </div>
						            <div class="col-md-6">
						            	<div class="form-group ">
						                  {!! Form::label('state', 'State:') !!}
						                  {!! Form::text('state',$default_state, ['class' => 'form-control  ui-widget'] ) !!}
						               </div>
						               
						            </div>
						         </div>
						         <div class="row">
						            <div class="col-md-12">
						               <div class="form-group">
						                  {!! Form::label('country', 'Country:') !!}
						                  {!! Form::text('country',$default_country, ['class' => 'form-control  ui-widget'] ) !!}
						               </div>
						            </div>
						         </div>
						      </fieldset>
						   </div>
						   <div class="col-md-4">
						      <!-- EMPTY Column-->
						   </div>
						</div>
						

						<div class="form-group radio-toolbar" id="deadoralive">
								{!! Form::radio('deadoralive','alive', $default_deadoralive_dead, ['id' => 'Alive' ]) !!} 
								{!! Form::label('Alive', 'Alive') !!}
								{!! Form::radio('deadoralive','dead',  $default_deadoralive_alive, ['id' => 'Dead' ] ) !!} 
								{!! Form::label('Dead', 'Dead') !!}
						</div>
		


						<div class="form-group">
							{!! Form::label('siblingno', 'Sibling No:') !!}
							{!! Form::select('siblingno', array(null => 'Choose...') + range(1,50), $default_siblingno , array('class' => 'styled-select form-control')) !!}
						</div>
						
						{{-- 
							$table->string('facebookid',120)->nullable();
							$table->string('googleid',120)->nullable();
							$table->string('email_id',120)->nullable();
						--}}

						<div class="form-group">
							{!! Form::label('notes', 'Facts and Details:', ["style" => "vertical-align: top;"]) !!}</br>
							{!! Form::textarea('notes',$default_description, ['class' => 'form-controller', 'size' => '30x5'] ) !!}
						</div>	


						<div class="form-group">
							{!! Form::submit($submitButtonText , ['class' => 'btn btn-primary']) !!}
						</div>	
						



