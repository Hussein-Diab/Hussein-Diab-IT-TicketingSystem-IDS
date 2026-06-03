<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('Users', function (Blueprint $table) {
        $table->id('Id');
        $table->string('Name', 100);
        $table->string('Email', 150)->unique();
        $table->string('Password', 255);
        $table->unsignedBigInteger('RoleId');
        $table->timestamps();

        $table->foreign('RoleId')
              ->references('Id')
              ->on('Roles');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};