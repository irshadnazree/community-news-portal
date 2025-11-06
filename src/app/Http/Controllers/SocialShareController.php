<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackSocialShareRequest;
use App\Models\SocialShareClick;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialShareController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin'])->only('analytics');
    }

    /**
     * Track a social share click.
     */
    public function track(TrackSocialShareRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $click = SocialShareClick::create([
            'platform' => $validated['platform'],
            'page_url' => $validated['page_url'],
            'page_type' => $validated['page_type'] ?? null,
            'news_post_id' => $validated['news_post_id'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Share tracked successfully',
            'data' => $click,
        ], 201);
    }

    /**
     * Display analytics dashboard for admin.
     */
    public function analytics(Request $request)
    {
        // Get filter parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $platform = $request->input('platform');
        $pageType = $request->input('page_type');

        // Build base query with filters (reused for all queries)
        $baseQuery = function ($query) use ($startDate, $endDate, $platform, $pageType) {
            if ($startDate) {
                $query->byDateRange($startDate, null);
            }
            if ($endDate) {
                $query->byDateRange(null, $endDate);
            }
            if ($platform && $platform !== 'all') {
                $query->byPlatform($platform);
            }
            if ($pageType && $pageType !== 'all') {
                $query->byPageType($pageType);
            }
        };

        // Get total shares
        $totalSharesQuery = SocialShareClick::query();
        $baseQuery($totalSharesQuery);
        $totalShares = $totalSharesQuery->count();

        // Get shares by platform
        $sharesByPlatformQuery = SocialShareClick::query();
        $baseQuery($sharesByPlatformQuery);
        $sharesByPlatform = $sharesByPlatformQuery
            ->select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'platform')
            ->toArray();

        // Get shares over time (grouped by date)
        $sharesOverTimeQuery = SocialShareClick::query();
        $baseQuery($sharesOverTimeQuery);
        $sharesOverTime = $sharesOverTimeQuery
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->count];
            })
            ->toArray();

        // Get top shared pages
        $topPagesQuery = SocialShareClick::query();
        $baseQuery($topPagesQuery);
        $topPages = $topPagesQuery
            ->select('page_url', DB::raw('count(*) as share_count'))
            ->groupBy('page_url')
            ->orderByDesc('share_count')
            ->limit(10)
            ->get();

        // Get shares by page type
        $sharesByPageTypeQuery = SocialShareClick::query();
        $baseQuery($sharesByPageTypeQuery);
        $sharesByPageType = $sharesByPageTypeQuery
            ->select('page_type', DB::raw('count(*) as count'))
            ->groupBy('page_type')
            ->get()
            ->pluck('count', 'page_type')
            ->toArray();

        return view('admin.analytics', compact(
            'totalShares',
            'sharesByPlatform',
            'sharesOverTime',
            'topPages',
            'sharesByPageType',
            'startDate',
            'endDate',
            'platform',
            'pageType'
        ));
    }
}
