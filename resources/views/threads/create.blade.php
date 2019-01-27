@extends('layouts.app')

@section('header')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a new Thread</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(count($errors))

                        <ul class="alert alert-danger">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    @endif

                    <form action="{{ route('threads.store') }}" method="POST" accept-charset="utf-8">
                    	<div class="form-group">
                           <label for="channel_id">Select a Channel</label>

                           <select class="form-control" name="channel_id" required>
                                <option value="">Choose one ...</option>}
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                @endforeach
                           </select>

                        </div>

                        <div class="form-group">
                    		@csrf
	                    	<label for="title">Title</label>
	                    	<input type="text" name="title" class="form-control" value="{{old('title')}}" required>
	                    </div>
	                    <div class="form-group"> 
	                    	<label for="body">Body</label>
                            <wysiwyg name="body"></wysiwyg>
 	                    </div>

                        <div class="g-recaptcha" data-sitekey="6LdquYwUAAAAAFCqnPKKb7Ykgz4dDY_r-_VdK2hl"></div>
	                    <div>
	                    	<button id="publishThread" type="submit" class="btn btn-primary float-right">Publish Thread</button>
	                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div> 	

@endsection