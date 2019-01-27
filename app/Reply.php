<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Reply extends Model
{
    use Favouritable, RecordsActivity;
    
    protected $fillable = [ 'user_id', 'body' ];

    protected $with = [ 'owner', 'favourites' ];

    protected $appends = ['favouritesCount', 'isFavourited'];

    protected static function boot() 
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });

    }

    public function owner ()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to a thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subSeconds(30));
    }

    public function mentionedUsers()
    {  
       preg_match_all('/@([\w\-]+)/i', $this->body, $matches);

       return $matches[1];
    }
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
        // return $this->thread->path()."#reply-{$this->id}";
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isBest()
    {
        // dd($this->thread->best_reply_id, $this->id);
        return $this->thread->best_reply_id == "$this->id";
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }


}
