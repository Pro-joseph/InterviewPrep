<?php

namespace App\Policies;

use App\Models\GeneratedQuestion;
use App\Models\User;

class GeneratedQuestionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, GeneratedQuestion $question): bool
    {
        return $user->id === $question->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, GeneratedQuestion $question): bool
    {
        return $user->id === $question->user_id;
    }
}