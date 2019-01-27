@component('profiles.activities.activity')
	@slot('heading')
		{{$profileUser->name}} Replied to a Thread 
		@if(null !== $activity->subject)
			<a href="{{ url($activity->subject->thread->path()) }}">{{ $activity->subject->thread->title}}</a>
		@endif
	@endslot

	@slot('body')
		@if(null !== $activity->subject)
			Title: <a href="{{ url($activity->subject->thread->path()) }}">{{ $activity->subject->body }}</a>
		@else
			(Since Deleted)
		@endif
	@endslot
@endcomponent
