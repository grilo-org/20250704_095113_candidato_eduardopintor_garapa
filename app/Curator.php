<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class Curator extends Model
{
    /**
     * @var string
     */
    protected $table = 'curator';

    /**
     * @var bool - Disabled timestamps columns
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'biography', 'city', 'description', 'facebook_url', 'firebase_id', 'instagram_url', 'name', 'occupation', 'picture_url', 'site_url', 'state'
    ];
}
