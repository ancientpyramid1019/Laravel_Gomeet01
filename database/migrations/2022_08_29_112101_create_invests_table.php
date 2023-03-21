<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schema_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->double('invest_amount');
            $table->integer('already_return_profit')->default(0);
            $table->double('total_profit_amount')->default(0);
            $table->dateTime('last_profit_time')->nullable();
            $table->dateTime('next_profit_time')->nullable();
            $table->string('capital_back');
            $table->integer('interest');
            $table->string('interest_type');
            $table->string('return_type');
            $table->integer('number_of_period');
            $table->integer('period_hours');
            $table->string('wallet');
            $table->string('status');
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
        Schema::dropIfExists('invests');
    }
};
