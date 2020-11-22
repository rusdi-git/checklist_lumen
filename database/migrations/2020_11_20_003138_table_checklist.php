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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('object_domain',50)->nullable();
            $table->string('object_id',30)->nullable();
            $table->dateTime('due')->nullable();
            $table->integer('urgency')->nullable();
            $table->text('description');
            $table->boolean('is_completed')->default(false);
            $table->dateTime('completed_at')->nullable();
            $table->string('task_id',10);
            $table->boolean('is_template')->default(false);

            // $table->foreign('created_by')->references('id')->on('user')->onDelete('set null');
            // $table->foreign('updated_by')->references('id')->on('user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('checklist');
        Schema::enableForeignKeyConstraints();
    }
}
