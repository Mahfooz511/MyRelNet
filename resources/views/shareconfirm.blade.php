@extends('forms')

@section('form-panel-heading')
	Share Family With Others
@endsection

@section('formcontent')

	<p>{{$message}}</p>
	
	<h4>Share link is </h4>
	<input type="text" value={{ url("share?sid=$shareid")}} style="width:700px;" onClick="this.setSelectionRange(0, this.value.length)">
	<p>Copy above link and share through Email, chat, SMS.</p>
	</br>
	</br>
	</br>

@endsection

			



