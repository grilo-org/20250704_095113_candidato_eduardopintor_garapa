<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * @var string
     */
    protected $table = 'type';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function establishment()
    {
        return $this->hasMany(Establishment::class);
    }
}
