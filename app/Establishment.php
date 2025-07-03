<?php

namespace GarAppa;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    /**
     * @var string
     */
    protected $table = 'establishment';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'firebase_id', 'city', 'neighborhood', 'number', 'phone', 'state', 'street', 'zipcode',
        'facebook_url', 'latitude', 'longitude', 'instagram_url', 'name', 'site_url', 'status', 'price_range_id'
    ];

    /**
     * Return all reviews of this Establishment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Return all indicators of this Establishment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indicators()
    {
        return $this->hasMany(UserEstablishmentIndicator::class);
    }

    /**
     * Return the photo of this Establishment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function photo()
    {
        return $this->hasOne(EstablishmentPhoto::class, 'establishment_id');
    }
}
