<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template', function (Blueprint $table) {
            $table->id();
            $table->string('name',150)->nullable();
            $table->text('description');
            $table->integer('due_interval')->nullable();
            $table->string('due_unit',10)->nullable();
        });

        Schema::create('template_item',function (Blueprint $table){
            $table->id();
            $table->foreignId('template_id')->constrained('template')->onDelete('cascade');
            $table->text('description');
            $table->integer('due_interval')->nullable();
            $table->string('due_unit',10)->nullable();
            $table->integer('urgency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_item');
        Schema::dropIfExists('template');
    }
}
