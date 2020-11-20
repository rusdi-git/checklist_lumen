<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('user');
            $table->integer('updated_by')->references('id')->on('user')->nullable()->onDelete('set null');
            $table->string('object_domain',50);
            $table->string('object_id',30);
            $table->dateTime('due')->nullable();
            $table->integer('urgency')->nullable();
            $table->text('description');
            $table->boolean('is_completed')->nullable();
            $table->dateTime('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist');
    }
}
