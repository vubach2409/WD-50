<?php

namespace App\Http\Controllers\Client;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function transactionHistory()
    {
        $transactions = Transaction::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('client.user', compact('transactions'));
    }
    
}
