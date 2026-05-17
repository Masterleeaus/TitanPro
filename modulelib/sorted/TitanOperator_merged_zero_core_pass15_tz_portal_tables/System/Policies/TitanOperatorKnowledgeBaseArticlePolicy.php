<?php

namespace App\Extensions\TitanOperator\System\Policies;

use App\Extensions\TitanOperator\System\Models\TitanOperatorKnowledgeBaseArticle;
use App\Models\User;

class TitanOperatorKnowledgeBaseArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TitanOperatorKnowledgeBaseArticle $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function edit(User $user, TitanOperatorKnowledgeBaseArticle $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function update(User $user, TitanOperatorKnowledgeBaseArticle $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function delete(User $user, TitanOperatorKnowledgeBaseArticle $item): bool
    {
        return $user->id === $item->user_id;
    }
}
