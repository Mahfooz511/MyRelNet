@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!
				</div>
				<a href="family/create">Create Family</a></br>
				<a href="person/create">Create First Person</a> </br>
				<a href="member/create">Add Members in Family</a> </br>
				<a href="test">Show Graph</a> </br>


			</div>
		</div>
	</div>
</div>
@endsection
