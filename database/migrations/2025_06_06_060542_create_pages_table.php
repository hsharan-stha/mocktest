<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->string('name')->nullable();
            $table->string('title');
            $table->integer('pageno');
            $table->text('page_image')->nullable();
            $table->longText('page_html')->nullable();

                    // New columns for quiz question and options
        // $table->text('question')->nullable();
        // $table->string('option1')->nullable();
        // $table->string('option2')->nullable();
        // $table->string('option3')->nullable();
        // $table->string('option4')->nullable();

        // The correct answer could be stored as string or integer (e.g., option number or option text)
        // $table->string('correct_answer')->nullable();
        
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
