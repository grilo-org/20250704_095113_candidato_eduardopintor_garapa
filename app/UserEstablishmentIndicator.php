<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class UserEstablishmentIndicator extends Model
{
    /**
     * @var string $table
     */
    protected $table = 'user_establishment_indicator';

    /**
     * Disable timestamps
     *
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * Return the User of this indicator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UserApp::class, 'id', 'user_id');
    }

    /**
     * Return the Establishment of this indicator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'id', 'establishments_id');
    }

}
