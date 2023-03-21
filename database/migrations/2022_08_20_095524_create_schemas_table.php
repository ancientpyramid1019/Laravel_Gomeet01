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
        Schema::create('schemas', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('name');
            $table->enum('type', ['fixed', 'range']);
            $table->double('fixed_amount')->default(0);
            $table->double('min_amount')->default(0);
            $table->double('max_amount')->default(0);
            $table->boolean('capital_back')->default(0);
            $table->string('badge')->nullable();
            $table->boolean('featured')->default(0);
            $table->boolean('status')->default(1);
            $table->enum('interest_type', ['percentage', 'fixed']);
            $table->integer('return_interest');
            $table->integer('return_period');
            $table->enum('return_type', ['period', 'lifetime']);
            $table->integer('number_of_period');
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
        Schema::dropIfExists('schemas');
    }
};
