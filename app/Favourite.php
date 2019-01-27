<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'favourited_id', 'favourited_type'];

    public function favourited()
    {
    	return $this->morphTo();
    }
}
