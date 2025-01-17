<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{
    /**
     * Determine if the user can edit the transaction.
     */
    public function update(User $user, Transaction $transaction)
    {
        // Allow if the user inserted the record or has the 'admin' role
        return $user->id === $transaction->user_id || $user->hasRole('administrator');
    }

    /**
     * Determine if the user can delete the transaction.
     */
    public function delete(User $user, Transaction $transaction)
    {
        // Allow if the user inserted the record or has the 'admin' role
        return $user->id === $transaction->user_id || $user->hasRole('administrator');
    }
}
