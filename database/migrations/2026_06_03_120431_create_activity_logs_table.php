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
        Schema::create('ActivityLogs', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('UserId');
            $table->unsignedBigInteger('TicketId')->nullable();
            $table->string('Action', 255);
            $table->timestamps();

            $table->foreign('UserId')
                ->references('Id')->on('Users');
            $table->foreign('TicketId')
                ->references('Id')->on('Tickets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
