<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    public function index(): JsonResponse
    {
        return SiteResource::collection(Auth::user()->sites()->paginate())->response();
    }

    public function store(StoreSiteRequest $request): JsonResponse
    {
        $attrs = $request->safe()->collect();

        $site = Auth::user()->sites()->create([
            ...$attrs->except(['scan_interval', 'scan_interval_type', 'dispatch_now'])->toArray(),
            'scan_interval' => get_interval_in_minutes($attrs['scan_interval'], $attrs['scan_interval_type']),
        ]);

        //TODO: dispatch job to scan site

        return SiteResource::make($site);
    }

    public function show(Site $site): JsonResponse
    {
        return SiteResource::make($site);
    }

    public function update(UpdateSiteRequest $request, Site $site): JsonResponse
    {
        $attrs = $request->safe()->collect();

        $site->update([
            ...$attrs->except(['scan_interval', 'scan_interval_type', 'dispatch_now'])->toArray(),
            'scan_interval' => get_interval_in_minutes($attrs['scan_interval'], $attrs['scan_interval_type']),
        ]);

        return SiteResource::make($site);
    }


    public function destroy(Site $site): Response
    {
        $site->delete();

        return response()->noContent();
    }
}
