<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generated_questions', function (Blueprint $table) {
            $table->enum('type', ['open', 'qcm'])->default('open')->after('concept_id');
        });
    }

    public function down(): void
    {
        Schema::table('generated_questions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};