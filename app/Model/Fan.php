<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Fan extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fans';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['star_id', 'fan_id', 'star_follow',
        'fan_follow','star_block','fan_block'];

    
}
