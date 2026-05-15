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
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_attempt_id');
            $table->unsignedBigInteger('page_id'); // The question page
            $table->unsignedBigInteger('question_type_id')->nullable();
            $table->unsignedBigInteger('sub_section_id')->nullable();
            $table->text('question_text');
            $table->string('selected_answer')->nullable(); // The answer selected by examinee (1, 2, 3, or 4)
            $table->string('correct_answer'); // The correct answer
            $table->boolean('is_correct')->default(false);
            $table->text('option1')->nullable();
            $table->text('option2')->nullable();
            $table->text('option3')->nullable();
            $table->text('option4')->nullable();
            $table->integer('question_number')->nullable(); // Question number in the exam
            $table->timestamps();
            
            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('question_type_id')->references('id')->on('question_types')->onDelete('set null');
            $table->index(['exam_attempt_id', 'page_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_answers');
    }
};
