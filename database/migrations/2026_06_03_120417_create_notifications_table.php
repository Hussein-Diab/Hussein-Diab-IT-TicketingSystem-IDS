<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('Notifications', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('UserId');
            $table->text('Message');
            $table->boolean('IsRead')->default(false);
            $table->timestamps();

            $table->foreign('UserId')
                ->references('Id')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
