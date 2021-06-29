<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class bookings extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bookings';

    protected $fillable = [
        "bookedSeats",
        "userName",
        "userEmail",
        "showID",
        "bookingAmount",
        "bookingStatus",
    ];
    protected $dates = [
        "bookingTime"   
    ];

    public static function getBookings($email){
        $bookings = static::where('userEmail','=',$email)->get();
        
        foreach($bookings as $booking){
            $booking->shows = Shows::where('_id','=',$booking->showID)->first();
        }
        return $bookings;
    }
}
