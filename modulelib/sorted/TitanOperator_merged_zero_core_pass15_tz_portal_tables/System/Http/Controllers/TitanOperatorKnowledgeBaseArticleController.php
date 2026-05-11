<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Requests\TitanOperatorKnowledgeBaseArticleRequest;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorKnowledgeBaseArticle;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TitanOperatorKnowledgeBaseArticleController extends Controller
{
    public function index()
    {
        return view('titan_operator::knowledge-base-article.index', [
            'items' => TitanOperatorKnowledgeBaseArticle::query()
                ->where('user_id', auth()->id())
                ->paginate(20),
            'title'       => __('Knowledge Base'),
            'description' => __('Manage your knowledge base articles.'),
        ]);
    }

    public function create()
    {
        return view('titan_operator::knowledge-base-article.edit', [
            'item'        => new TitanOperatorKnowledgeBaseArticle,
            'action'      => route('dashboard.titan_operator.knowledge-base-article.store'),
            'method'      => 'POST',
            'operators'    => TitanOperator::query()->where('user_id', auth()->id())->get(),
            'title'       => __('Create Article'),
            'description' => __('Create a new knowledge base article to help users find answers to their questions.'),
        ]);
    }

    public function store(TitanOperatorKnowledgeBaseArticleRequest $request): RedirectResponse
    {
        TitanOperatorKnowledgeBaseArticle::query()->create($request->validated());

        return redirect()->route('dashboard.titan_operator.knowledge-base-article.index')
            ->with([
                'type'    => 'success',
                'message' => __('Knowledge base article created.'),
            ]);
    }

    public function edit(TitanOperatorKnowledgeBaseArticle $knowledgeBaseArticle)
    {
        $this->authorize('edit', $knowledgeBaseArticle);

        return view('titan_operator::knowledge-base-article.edit', [
            'item'        => $knowledgeBaseArticle,
            'action'      => route('dashboard.titan_operator.knowledge-base-article.update', $knowledgeBaseArticle->getKey()),
            'method'      => 'PUT',
            'operators'    => TitanOperator::query()->where('user_id', auth()->id())->get(),
            'title'       => __('Edit Article'),
            'description' => __('Edit a knowledge base article to help users find answers to their questions.'),
        ]);
    }

    public function update(TitanOperatorKnowledgeBaseArticleRequest $request, TitanOperatorKnowledgeBaseArticle $knowledgeBaseArticle): RedirectResponse
    {
        $this->authorize('update', $knowledgeBaseArticle);

        $knowledgeBaseArticle->update($request->validated());

        return redirect()->route('dashboard.titan_operator.knowledge-base-article.index')
            ->with([
                'type'    => 'success',
                'message' => __('Knowledge base article updated.'),
            ]);
    }

    public function destroy(TitanOperatorKnowledgeBaseArticle $knowledgeBaseArticle): RedirectResponse
    {
        $this->authorize('delete', $knowledgeBaseArticle);

        $knowledgeBaseArticle->delete();

        return redirect()->route('dashboard.titan_operator.knowledge-base-article.index')
            ->with([
                'type'    => 'success',
                'message' => __('Knowledge base article successfully deleted.'),
            ]);
    }
}
