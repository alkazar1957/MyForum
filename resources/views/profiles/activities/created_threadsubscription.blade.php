@component('profiles.activities.activity')
	@slot('heading')
		{{$profileUser->username}} Subscribed to
		@if(null !== $activity->subject)
			<a href="{{ url($activity->subject->thread->path()) }}">{{ $activity->subject->thread->title}}</a>
		@endif
	@endslot

	@slot('body')
		@if(null !== $activity->subject)
			Title: <a href="{{ url($activity->subject->thread->path()) }}">{{ str_limit($activity->subject->thread->body, 30, ' More...') }}</a>
		@else
			(Since Deleted)
		@endif
	@endslot
@endcomponent
