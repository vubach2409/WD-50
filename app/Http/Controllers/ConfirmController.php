<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmController extends Controller
{
    public function Confirm_cod(){
        return view('client.confirm');
    }
    public function Confirm_vnpay(){
        return view('client.confirmvnpay');
    }
}
