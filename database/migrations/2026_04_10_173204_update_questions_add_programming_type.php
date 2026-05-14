<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestionsAddProgrammingType extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->enum('question_type', ['mcq', 'short_answer', 'programming'])
                  ->default('mcq')
                  ->after('question_text');
        });

        Schema::table('student_answers', function (Blueprint $table) {
            $table->text('code_answer')->nullable()->after('answer_text');
            $table->text('code_output')->nullable()->after('code_answer');
            $table->string('language')->nullable()->after('code_output');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->enum('question_type', ['mcq', 'short_answer'])->default('mcq');
        });
        Schema::table('student_answers', function (Blueprint $table) {
            $table->dropColumn(['code_answer', 'code_output', 'language']);
        });
    }
}