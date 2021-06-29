<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shows;
use App\Models\bookings;
use Illuminate\Support\Arr;
use PHPMailer\PHPMailer\PHPMailer;


class bookingController extends Controller
{   
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $show = Shows::where('_id', $request->get('showID'))->first();
        $show_booked = $show->bookedSeats = [];
        $bookedsheets = $request->get('bookedSeats');
        for($i = 0 ; $i < count($request->get('bookedSeats')) ; $i++ )
        {
            $flag =  array_search($bookedsheets[$i],$show_booked);
            if($flag > '0'){
                return response('Ticket already Booked', 400);
            }
        }      
        $booking = new bookings();
        $booking->bookedSeats = $request->get('bookedSeats');
        $booking->userName = $request->get('userName');
        $booking->showID = $request->get('showID');
        $booking->bookingAmount = $request->get('bookingAmount');
        $booking->userEmail = $request->get('userEmail');
        $booking->bookingStatus = 'booked';
        $booking->bookingTime = new \DateTime();
        $booking->save();
       
        $mail = $this->sendMail($booking,$show,'Booked');

        array_push($show_booked,$bookedsheets);
        $show_booked = Arr::flatten($show_booked);
        $show->bookedSeats = $show_booked;
        $show->update([$show]);
        return [$booking, $mail];
    }


    function sendMail($booking,$show,$status){
        $sheets = implode(", ",$booking->bookedSeats);
        $text             = <<<MOHSIN_MUNSHI

        Dear {$booking->userName} <br>
            <br>
            Thank You for your using our Ticket booking Service  <br>
            <br>
            Your Ticket of Bleow show is {$status} <br>
            <br>
            Name : {$show->name}  <br>
            Show Time : {$show->showTime} <br>
            Seats: {$sheets} <br>
            Vanue: {$show->venue} <br>
            Total Price:{$booking->bookingAmount} <br>
            <br>
        Thanks & Regards <br>
        Ticket Booking <br>
        
MOHSIN_MUNSHI;
        $mail             = new PHPMailer(); // create a n
        $mail->SMTPDebug  = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth   = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->isSMTP();  // tell the class to use SMTP

        $mail->Host       = "smtp.gmail.com";
        $mail->Port       = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "mailsender.mohsin@gmail.com";
        $mail->Password = "yasin@123";
        $mail->SetFrom("mailsender.mohsin@gmail.com", 'Mohsin');
        $mail->Subject = "Your Ticket for - {$show->userName} is {$status}";
        $mail->Body    = $text;
        $mail->IsHTML(true);
        $mail->AddAddress($booking->userEmail,$booking->userName);
        if ($mail->Send()) {
            return 'Email Sended Successfully';
        } else {
            var_dump($mail);
            exit;
            return 'Failed to Send Email';
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shows =  bookings::getBookings($id);

        return $shows;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $booking = bookings::where('_id', $request->get('_id'))->first();
        $show = Shows::where('_id', $booking->showID)->first();
        $booking->update($request->all());

        $sheets = $booking->bookedSeats;
        $showSheets = gettype($show->bookedSeats) == "array" ? $show->bookedSeats : [];
        if(count($sheets) > 0){
            for($i=0; $i < count($sheets); $i++){
                $showSheets = array_filter($showSheets,function($var) use($sheets,$i) {
                    return($var != $sheets[$i]);
                });
            }
        }

        $show->bookedSeats = array_values($showSheets);
        $show->update([$show]);
        $mail = $this->sendMail($booking,$show,'Cancelled');
        return $booking;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
