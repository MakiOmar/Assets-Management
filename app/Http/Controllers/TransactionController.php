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
        // Check if the user is an admin
        if ($request->user()->hasRole('administrator')) {
            // Admin: Get all transactions with pagination (10 per page)

            $transactions = Transaction::orderBy('date', 'desc')->paginate(10);
        } else {
            // Non-admin: Get transactions only for the logged-in user with pagination (10 per page)
            $transactions = Transaction::getTransactionsByUser($request->user()->id, 10);
        }

        // Return the view with paginated transactions
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
    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Request $request, Transaction $transaction)
    {
         // Authorize the action
         $this->authorize('delete', $transaction);
        // Check if the transaction belongs to the authenticated user
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this transaction.'
            ], 403);
        }

        $transaction->delete();

        // Handle XHR (AJAX) Request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully.'
            ]);
        }

        // Handle Regular HTTP Request
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
