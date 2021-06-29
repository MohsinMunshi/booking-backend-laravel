<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Shows extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'shows';

    protected $fillable = [
        "name",
        "venue",
        "seats",
        "price",
        "showTime",
        "bookedSeats"
    ];

    protected $dates = [
        "showTime"   
    ];

    



   
   


}
