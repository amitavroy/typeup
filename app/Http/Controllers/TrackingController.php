<?php

namespace App\Http\Controllers;

use App\Http\Requests\InitSearchRequest;
use App\Http\Requests\TrackClickRequest;
use App\Models\ClickEvent;
use App\Models\Search;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    /**
     * Initialize a new search session.
     */
    public function initSearch(InitSearchRequest $request): JsonResponse
    {
        $site = $request->input('validated_site');

        $search = Search::create([
            'site_id' => $site->id,
            'search_id' => Str::uuid()->toString(),
            'metadata' => $request->input('metadata', []),
        ]);

        return response()->json([
            'search_id' => $search->search_id,
        ]);
    }

    /**
     * Track a click event.
     */
    public function trackClick(TrackClickRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Verify that the search_id belongs to the site_key
        $search = Search::where('search_id', $validated['search_id'])
            ->whereHas('site', function ($query) use ($validated) {
                $query->where('site_key', $validated['site_key']);
            })
            ->first();

        if (! $search) {
            return response()->json([
                'error' => 'Search ID does not belong to the provided site key',
            ], 400);
        }

        $clickEvent = ClickEvent::create([
            'search_id' => $search->search_id,
            'content_id' => $validated['content_id'],
            'position' => $validated['position'],
            'metadata' => $validated['metadata'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'click_event_id' => $clickEvent->id,
        ]);
    }

    /**
     * Serve the tracking script.
     */
    public function serveScript(): Response
    {
        $scriptContent = file_get_contents(public_path('js/tracker.js'));

        return response($scriptContent)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour
    }
}
