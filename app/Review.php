<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
