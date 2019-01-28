@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
	<div class="col-sm-8">

		<div class="card">

			<div class="card-header">
                <img src="/profiles/showAvatar/{{ $profileUser->username }}" width="100px" id="profile_picture" class="bg-info rounded-circle m-auto">
                <br>

				{{ $profileUser->username }}

				<br>
				<small>
					Member Since 
					{{ $profileUser->created_at->diffForHumans() }}
				</small>

			</div>

			<div class="card-body">

				{{ $profileUser->body }}

			</div>

			<div class="card-footer">



			</div>

		</div>

	</div>

	<div class="col-md-4">

        <div class="card">

            <div class="card card-header">
            	<h4>Activity</h4>
            </div>

            	@forelse($activities as $date => $activity)

            		<p class="page-header ml-4 ">
            			{{ \Carbon\Carbon::createFromFormat('Y-m-d', $date)->toFormattedDateString() }}
            		</p>
            		@foreach($activity as $record)
            			@if(view()->exists('profiles.activities.'.$record->type))
	            			@include("profiles.activities.{$record->type}", ['activity' => $record])
	            		@endif
            		@endforeach
            	@empty

            		<p>There is no activity yet for this user.</p>

            	@endforelse

        </div>

		<div class="card card-header">Threads by {{ $profileUser->username }} </div>
		@foreach($threads as $thread)

	        <div class="card">

	            <div class="card card-header">
	            	<div class="level">
	            	<span class="flex">
	            		Title: 
	            		<a href="{{ route('threads.show', ['channel' => $thread->channel, 'thread' => $thread->id]) }}">
	            			{{ $thread->title }}
	            		</a>
	            	</span>
	            	<span class="">
	            		Posted: {{ $thread->created_at->diffForHumans() }}
	            	</span>
	            	</div>
	            </div>

	            <div class="card card-body">

	            		{{ $thread->body }}
	            			
	            </div>

	        </div>

		@endforeach

		{{ $threads->links() }}
	</div>
	</div>
</div>

@endsection