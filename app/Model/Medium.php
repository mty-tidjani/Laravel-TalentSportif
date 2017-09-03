<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media';

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
    protected $fillable = ['titre', 'description', 'discr', 'user_id','album_id'];

    protected $dates = ['created_at','updated_at'];

    
}
