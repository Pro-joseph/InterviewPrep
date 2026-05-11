
Schema::create('domains', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('color', 7); // ex: #3B82F6
    $table->timestamps();
});

Schema::create('concepts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('explanation');
    $table->enum('difficulty', ['junior', 'mid', 'senior']);
    $table->enum('status', ['a_revoir', 'en_cours', 'maitrise'])->default('a_revoir');
    $table->softDeletes(); // pour le bonus
    $table->timestamps();
});

Schema::create('generated_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('concept_id')->constrained()->cascadeOnDelete();
    $table->json('questions'); // tableau des 5 questions Groq
    $table->timestamps();
});
