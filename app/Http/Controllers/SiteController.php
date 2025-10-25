<?php

namespace App\Http\Controllers;

use App\Actions\SiteCreate;
use App\Http\Requests\SiteCreateRequest;
use App\Models\Site;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $sites = Site::query()
            ->orderBy('name', 'asc')
            ->paginate(10);

        return Inertia::render('sites/index', [
            'sites' => $sites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $site = new Site;

        return Inertia::render('sites/create', [
            'site' => $site,
        ]);
    }

    public function show(Site $site): Response
    {
        return Inertia::render('sites/show', [
            'site' => $site,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiteCreateRequest $request, SiteCreate $siteCreate)
    {
        $site = $siteCreate->execute($request->validated());

        return redirect()->route('sites.show', $site->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
