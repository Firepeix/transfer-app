<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('from_wallet_id');
            $table->foreignId('to_wallet_id');
            $table->integer('amount');
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('from_wallet_id')->on('wallets')->references('id');
            $table->foreign('to_wallet_id')->on('wallets')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
