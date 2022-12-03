<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

use Jenssegers\Mongodb\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Model
{
    protected $connection = 'mongodb';
    use   HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'user_id',
        'social',
        'temp_social',
        'api_token'
    ];

    // created_at, updated_at to unix time
    public function setCreatedAtAttribute($date)
    {
        $dateTime=(array) $date;
        $this->attributes['created_at'] =(int) $dateTime["milliseconds"];
    }

    public function setUpdatedAt($date)
    {
        $dateTime=(array) $date;
        $this->attributes['updated_at'] =(int) $dateTime["milliseconds"];
    }


}
