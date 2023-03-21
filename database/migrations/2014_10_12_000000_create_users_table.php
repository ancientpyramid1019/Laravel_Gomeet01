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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ranking_id')->nullable();
            $table->json('rankings')->nullable();
            $table->string('avatar')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('country');
            $table->string('phone');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->enum('gender', ['male', 'female', 'other', ''])->default('');
            $table->date('date_of_birth')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('address')->nullable();
            $table->double('balance')->default(0);
            $table->double('profit_balance')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('kyc')->default(0);
            $table->json('kyc_credential')->nullable();
            $table->text('google2fa_secret')->nullable();
            $table->boolean('two_fa')->default(false);
            $table->boolean('deposit_status')->default(1);
            $table->boolean('withdraw_status')->default(0);
            $table->boolean('transfer_status')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
