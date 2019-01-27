@component('profiles.activities.activity')
	@slot('heading')
		{{$profileUser->name}} Created a thread
	@endslot

	@slot('body')
		@if(null !== $activity->subject)
			Title: <a href="{{ url($activity->subject->path())}}"> {{ $activity->subject->title }}</a>
		@else
			(Since Deleted)
		@endif
	@endslot
@endcomponent
