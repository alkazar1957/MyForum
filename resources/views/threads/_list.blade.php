<div class="card">
    <div class="card-header">
        Forum Threads ({{ $threads->count() }})
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @forelse ( $threads as $thread)
    <div class="card-body">
        <div class="card-header">
            <div class="level">

            <a href="{{ route('profiles.show', $thread->creator->username) }}">
                <img src="/profiles/showAvatar/{{$thread->creator->username}}" width="30px" id="profile_picture" class="bg-info rounded-circle align-text-bottom mr-2">
            </a>

        		<h4 class="flex">
                    <a class="thread" href="{{ $thread->path() }}">
                        @if(auth()->check() && $updated = $thread->hasUpdatesFor(auth()->user()))
                            <strong>
                                {{ $thread->title }}
                                <small style="color:black; font-size: 50%;">( {{ $updated }} Update )</small>
                            </strong>
                       @else
                           {{ $thread->title }}
                       @endif
                    </a>
                </h4>

                @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post" accept-charset="utf-8">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
                @endcan

                <a href="{{ $thread->path() }}" class="ml-4 font-weight-bold">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }} 
                </a>

            </div>
                <small>
                    Posted by 
                        <a href="{{route('profiles.show', $thread->creator->username)}}">
                            {{ $thread->creator->username }}
                        </a>
                        {{ $thread->created_at->diffForHumans() }}
                        <span class="{{ $thread->locked ? 'bg-warnings fa fa-lock' : '' }}" style="color:red;">
                            {{ $thread->locked ? ' Thread Locked' : ''}}
                        </span>
                </small>
        </div>

        <div class="card-body">
    		<div class="body level">
    			<div class="flex trix-content">{!! $thread->body !!}</div>
                <div>
                    {{ $thread->views}} Views
                </div>
    		</div>
        </div>
    </div>
    @empty
        <p>There are no Threads here at the moment</p>
    @endforelse
    <hr>
	<div class="align-self-end mr-5">
		{{$threads->links()}}
	</div>

</div>
