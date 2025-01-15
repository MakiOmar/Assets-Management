<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('cascade');
            $table->foreignId('liability_id')->nullable()->constrained('liabilities')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('type'); // E.g., "Deposit", "Withdrawal"
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
