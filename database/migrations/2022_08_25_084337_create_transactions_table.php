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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('from_user_id')->nullable();
            $table->string('from_model')->default('User');
            $table->unsignedInteger('target_id')->nullable();
            $table->string('target_type')->nullable();
            $table->string('tnx')->unique();
            $table->string('description')->nullable();
            $table->string('amount');
            $table->string('type');
            $table->string('charge')->default(0);
            $table->string('final_amount')->default(0);
            $table->string('account')->nullable();
            $table->string('pay_currency')->nullable();
            $table->string('pay_amount')->nullable();
            $table->text('manual_field_data')->nullable();
            $table->text('approval_cause')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
