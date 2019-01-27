@extends('layouts.app')

@section('content')

<div class="container">

	<div class="col-sm-10 col-sm-offset-2">

		<div class="card">

			<div class="card-header">

				Edit Your Profile:
				<small>(Tell the world who you are!)</small>

			</div>

			<div class="card-body">

				<form action="{{route('profiles.update', ['user' => $user->name])}}" method="post" accept-charset="utf-8">
					@csrf
					@method('PATCH')
					<textarea id="body" name="body" class="form-control">{{$user->body}}</textarea>
					<div class="pull-right">
					<button type="submit" class="btn btn-sm btn-primary">Save Profile</button>
					<a href="javascript:history.back()" class="btn btn-sm btn-primary">Cancel</a>
						
					</div>
				</form>

			</div>

			<div class="card-footer">

			</div>

		</div>

	</div>

</div>

@endsection