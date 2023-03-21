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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('name');
            $table->enum('type', ['auto', 'manual'])->default('manual');
            $table->string('gateway_code')->unique();
            $table->double('charge')->default(0);
            $table->enum('charge_type', ['percentage', 'fixed']);
            $table->double('minimum_deposit');
            $table->double('maximum_deposit');
            $table->double('rate');
            $table->text('supported_currencies')->nullable();
            $table->string('currency');
            $table->string('currency_symbol');
            $table->text('credentials');
            $table->longText('payment_details')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('gateways');
    }
};
