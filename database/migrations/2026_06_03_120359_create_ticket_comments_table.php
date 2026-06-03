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
        Schema::create('TicketComments', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('TicketId');
            $table->unsignedBigInteger('UserId');
            $table->text('Body');
            $table->timestamps();

            $table->foreign('TicketId')
                ->references('Id')->on('Tickets');
            $table->foreign('UserId')
                ->references('Id')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
