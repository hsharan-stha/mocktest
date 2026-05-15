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
        // Drop the old type column if it exists
        if (Schema::hasColumn('pages', 'type')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
        
        // Add new type_id foreign key column
        Schema::table('pages', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable()->after('correct_answer');
        });
        
        // Add foreign key constraint
        Schema::table('pages', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('question_types')->onDelete('set null');
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
            // Drop foreign key and column
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
            // Restore old type column
            $table->string('type')->nullable()->after('correct_answer');
        });
    }
};
