<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('review')->change();
        });
    }

    public function down(): void
    {
        Schema::table('concepts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->enum('status', ['a_revoir', 'en_cours', 'maitrise'])->default('a_revoir')->change();
        });
    }
};