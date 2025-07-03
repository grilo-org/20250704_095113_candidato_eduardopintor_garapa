<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class EstablishmentPhoto extends Model
{
    /**
     * @var string
     */
    protected $table = 'photo';

    /**
     * @var array
     */
    protected $fillable = [
        'filename', 'establishment_id'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'id', 'establishment_id');
    }
}
