<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a list of transactions.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::getRecentTransactionsByUser($request->user()->id);

        // XHR Request: Return JSON response
        if ($request->ajax()) {
            return response()->json(['transactions' => $transactions]);
        }

        // HTTP Request: Return view
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|string|max:255',
        ]);

        $transaction = Transaction::recordTransaction(array_merge($request->all(), [
            'user_id' => $request->user()->id,
        ]));

        // XHR Request: Return JSON response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction recorded successfully.',
                'transaction' => $transaction,
            ]);
        }

        // HTTP Request: Redirect with success message
        return redirect()->route('transactions.index')->with('success', 'Transaction recorded successfully.');
    }
}
