<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shows;

date_default_timezone_set('Asia/Kolkata');

class showsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ["data" => Shows::where('showTime' ,'>',new \DateTime())->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $show = new Shows();
        $show->name = $request->get('name');
        $show->venue = $request->get('venue');
        $show->seats = $request->get('seats');
        $show->price = $request->get('price');
        $show->showTime = $request->get('showTime');
        $show->save();
        return $show;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
