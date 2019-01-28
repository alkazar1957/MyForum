@component('profiles.activities.activity')
	@slot('heading')
		{{$profileUser->username}} favourited a reply to
		@if(null !== $activity->subject)
			<a href="{{ url($activity->subject->favourited->path()) }}">{{ $activity->subject->favourited->thread->title}}</a>
		@endif
	@endslot

	@slot('body')
		@if(null !== $activity->subject)
			Title: <a href="{{ url($activity->subject->favourited->path()) }}">{{ $activity->subject->favourited->body }}</a>
		@else
			(Since Deleted)
		@endif
	@endslot
@endcomponent
