<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Support\Facades\Redis;
use App\Traits\RecordsVisits;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivity, Searchable;

    public $asYouType = true;

    protected $fillable = [ 'user_id', 'channel_id', 'title', 'body', 'views', 'slug', 'best_reply_id', 'locked' ];

    protected $with =  [ 'creator', 'channel' ];

    protected $appends = [ 'isSubscribedTo'];

    // TODO: FIND OUT WHY WHEN USING CASTS
    // THE RESULT IS RETURNED AS TRUE EVEN WHEN FALSE.
    // protected $casts = [
    //     'locked' => 'boolean'
    // ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created( function($thread) {
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function path()
    {
    	return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function replies ()
    {
    	return $this->hasMany(Reply::class);
    }

    public function addReply ($reply)
    {
    	$reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function markBestReply($reply)
    {
        // $this->best_reply_id = $reply->id;

        // $this->save();
        $reply->thread->update(['best_reply_id' => $reply->id]);
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function creator ()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function addThread($thread)
    {
        return $this->create($thread);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        return $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);        
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return new Visits($this);
        // return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

}
