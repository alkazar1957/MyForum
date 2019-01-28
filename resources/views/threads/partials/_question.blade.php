
<div class="card" v-if="editing && ! locked">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <input type="text"  class="form-control" v-model="form.title">
            </span>

        </div>
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

    		<div class="form-group"> 
                <wysiwyg v-model="form.body" name="body"></wysiwyg>
    		</div>

    </div>

    <div class="card-footer" v-if="authorize('updateThread', thread)">
    	<button type="button" class="btn btn-xs btn-primary pull-right" @click="update">Update</button>
    	<button type="button" class="btn btn-xs btn-warning pull-right mr-2" @click="resetForm">Cancel</button>

            @can('update', $thread)
            <form action="{{ $thread->path() }}" method="post" accept-charset="utf-8">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-xs btn-danger pull-right mr-2">Delete Thread</button>
            </form>
            @endcan
    </div>
    
</div>

<div class="card" v-else>
    <div class="card-header">
        <div class="level">
            <span class="flex">
                <a href="{{ route('profiles.show', $thread->creator->username) }}">
                <img src="/profiles/showAvatar/{{$thread->creator->username}}" width="50px" id="profile_picture" class="bg-info rounded-circle m-auto align-text-bottom">
                <!-- <br> -->

                    {{ $thread->creator->username }}
                </a>
                Posted ... <span v-text="form.title"></span>
            </span>

        </div>
    </div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

    	<article>
    		<div class="thread-body trix-content" v-html="form.body"></div>
    	</article>

    </div>

    <div class="card-footer" v-if="authorize('owns', thread) && ! locked">
    	<button type="button" class="btn btn-xs btn-primary pull-right" @click="editing = true">Edit</button>
    </div>
    
</div>
