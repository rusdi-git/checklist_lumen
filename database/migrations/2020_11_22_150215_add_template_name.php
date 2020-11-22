<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checklist',function (Blueprint $table) {
            $table->string('template_name',150)->nullable();
            $table->integer('due_interval')->nullable();
            $table->string('due_unit',10)->nullable();
        });
        Schema::table('item',function (Blueprint $table) {
            $table->integer('due_interval')->nullable();
            $table->string('due_unit',10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checklist',function (Blueprint $table) {
            $table->dropColumn(['template_name','due_interval','due_unit']);
        });
        Schema::table('item',function (Blueprint $table) {
            $table->dropColumn(['due_interval','due_unit']);
        });
    }
}
