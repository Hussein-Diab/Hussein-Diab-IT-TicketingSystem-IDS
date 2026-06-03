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
        Schema::create('Tickets', function (Blueprint $table) {
            $table->id('Id');
            $table->string('RefNumber', 20)->unique();
            $table->string('Title', 255);
            $table->text('Description');
            $table->unsignedBigInteger('UserId');
            $table->unsignedBigInteger('AssignedTo')->nullable();
            $table->unsignedBigInteger('CategoryId');
            $table->unsignedBigInteger('PriorityId');
            $table->unsignedBigInteger('StatusId');
            $table->timestamps();

            $table->foreign('UserId')
                ->references('Id')->on('Users');
            $table->foreign('AssignedTo')
                ->references('Id')->on('Users');
            $table->foreign('CategoryId')
                ->references('Id')->on('Categories');
            $table->foreign('PriorityId')
                ->references('Id')->on('Priorities');
            $table->foreign('StatusId')
                ->references('Id')->on('Statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
