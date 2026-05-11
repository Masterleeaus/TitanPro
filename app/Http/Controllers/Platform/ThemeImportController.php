<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Support\ThemePackManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;

/**
 * Handles public theme share URL resolution.
 *
 * GET /theme/import/{token}
 *   - Resolves the token and redirects to the UI Studio with pre-populated
 *     import URL so the user can preview and install the shared theme.
 */
class ThemeImportController extends Controller
{
    public function __invoke(Request $request, string $token, ThemePackManager $manager): RedirectResponse
    {
        if (! Schema::hasTable('shared_themes')) {
            return redirect()->back()->with('error', 'Theme sharing is not available yet.');
        }

        $data = $manager->resolveShareToken($token);

        if (! $data) {
            return redirect()->back()->with('error', 'Theme link not found or has expired.');
        }

        // Resolve the UI Studio URL for the authenticated user's panel.
        // Default to the titanpro admin UI Studio page.
        $studioUrl = '/titanpro/ui-studio?import_token=' . urlencode($token);

        return redirect($studioUrl);
    }
}
