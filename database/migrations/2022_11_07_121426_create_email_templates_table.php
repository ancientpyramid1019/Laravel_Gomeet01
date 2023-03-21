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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('for')->default('User');
            $table->string('banner')->nullable();
            $table->string('title')->nullable();
            $table->string('subject')->nullable();
            $table->text('salutation')->nullable();
            $table->longText('message_body');
            $table->string('button_level')->nullable();
            $table->string('button_link')->nullable();
            $table->boolean('footer_status')->default(true);
            $table->text('footer_body')->nullable();
            $table->boolean('bottom_status')->default(true);
            $table->string('bottom_title')->nullable();
            $table->text('bottom_body')->nullable();
            $table->text('short_codes')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('email_templates');
    }
};
