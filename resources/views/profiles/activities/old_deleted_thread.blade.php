@component('profiles.activities.activity')
	@slot('heading')
		{{$profileUser->name}} Deleted a thread
	@endslot

	@slot('body')
		@if(null !== $activity->subject)
			Title: {{ $activity->subject->title }}
		@else
			(Since Deleted)
		@endif
	@endslot
@endcomponent
