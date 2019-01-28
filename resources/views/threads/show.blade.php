@extends('layouts.app')

@section('header')
    <link href="{{ asset('css/jquery.atwho.css') }}" rel="stylesheet">
    <script>
        window.thread = <?= json_encode(['owner' => $thread->user_id, 'best_reply_id' => $thread->best_reply_id]); ?>
    </script>
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template>
<div class="container-fluid" v-cloak>
    <div class="row">
        <div class="col-md-8">

            @include('threads.partials._question')

            <div class="replies-collection center-block">
                <replies @added="repliesCount++" @removed="repliesCount--"></replies>
           </div>

        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                  This thread was Publshed {{ $thread->created_at->diffForHumans() }} by 
                    <a href="{{ route('profiles.show', $thread->creator->username) }}">{{ $thread->creator->username }}</a>
                    and currently has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                    <br>
                </div>

                <div class="card-body">
                    <p>
                        <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                        <button type="button" class="fa btn btn-danger" 
                            :class="locked ? 'fa-unlock' : 'fa-lock'"
                            v-if="authorize('isAdmin')" 
                            @click="toggleLock"
                            v-text="locked ? ' Unlock Thread' : ' Lock Thread'">
                        </button>
                    </p>
                </div>
            </div>
            
        </div>
    </div>

</div> 	
</thread-view>
@endsection