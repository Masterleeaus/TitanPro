<?php

namespace App\Extensions\TitanCommand\System\Http\Controllers;

use App\Extensions\TitanCommand\System\Models\TitanCommandPost;
use App\Extensions\TitanCommand\System\Services\PostGenerationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TitanCommandPostController extends Controller
{

    private function tenantIds(): array
    {
        $userId = (int) (\Illuminate\Support\Facades\Auth::id() ?? 0);
        // MVP rule: company_id == user_id
        $companyId = $userId;
        return [$companyId, $userId];
    }

    public function regenerateContent(Request $request, TitanCommandPost $post): JsonResponse
    {
        $this->authorize('update', $post->agent);

        $generationService = new PostGenerationService;
        $result = $generationService->generatePost($post->agent, [
            'base_content'     => $post->content,
            'include_hashtags' => $post->agent->hashtag_count ? true : false,
            'post_types'       => $post->agent->post_types,
        ]);

        if (! data_get($result, 'success')) {
            return response()->json([
                'success' => false,
                'message' => data_get($result, 'error', __('Unable to regenerate post content right now.')),
            ], 422);
        }

        $metadata = array_merge($post->ai_metadata ?? [], $result['metadata'] ?? []);
        $metadata['regenerated_at'] = now()->toIso8601String();

        if (! empty($result['cta'])) {
            $metadata['cta'] = $result['cta'];
        }

        $post->update([
            'content'     => $result['content'] ?? $post->content,
            'hashtags'    => array_values($result['hashtags'] ?? []),
            'post_type'   => $result['post_type'] ?? $post->post_type,
            'ai_metadata' => $metadata,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('Post content regenerated successfully.'),
            'post'    => $post->fresh(['agent', 'platform', 'socialPost']),
        ]);
    }
}
