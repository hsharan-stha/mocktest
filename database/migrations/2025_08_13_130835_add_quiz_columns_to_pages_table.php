<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
           $table->text('question')->nullable();
        $table->string('option1')->nullable();
        $table->string('option2')->nullable();
        $table->string('option3')->nullable();
        $table->string('option4')->nullable();
        $table->string('correct_answer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
                   $table->dropColumn(['question', 'option1', 'option2', 'option3', 'option4', 'correct_answer']);

        });
    }
};
