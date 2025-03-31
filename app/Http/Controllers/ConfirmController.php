<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ConfirmController extends Controller
{
    public function Confirm_cod(){
        $shippings = Shipping::all();
        return view('client.confirm',compact('shippings'));
    }
    public function Confirm_vnpay(){
        $shippings = Shipping::all();
        return view('client.confirmvnpay',compact('shippings'));
    }
}
