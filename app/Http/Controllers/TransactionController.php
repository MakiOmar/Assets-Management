<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::getRecentTransactionsByUser($request->user()->id);
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required',
        ]);
        Transaction::recordTransaction($request->all());
        return response()->json(['message' => 'Transaction recorded successfully']);
    }
}
