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
        Schema::create('TicketAttachments', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('TicketId');
            $table->string('FileName', 255);
            $table->string('FilePath', 255);
            $table->integer('FileSize')->nullable();
            $table->timestamps();

            $table->foreign('TicketId')
                ->references('Id')->on('Tickets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
