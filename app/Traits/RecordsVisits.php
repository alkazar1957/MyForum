<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;
use App\Visits;

trait RecordsVisits
{
    public function recordVisits()
    {
        Redis::incr($this->visitsCacheKey());
        return $this;
    }

    public function visits()
    {
    	return new Visits($this);
        // return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());

        return $this;
    }

    public function visitsCacheKey()
    {
        return "threads.{$this->id}.visits"    ;
    }
}