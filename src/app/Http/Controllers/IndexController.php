<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tracker\Index\DashboardRequest;
use App\Http\Resources\Tracker\Resource\IndexSelect;
use App\Services\Tracker\ResourceService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class IndexController
{
    public function __construct(
        protected ResourceService $resourceService,
    ) {}


    public function index()
    {
        return Inertia::render('Index/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function dashboard(DashboardRequest $request)
    {
        $selectedResourceId = $request->input('selectedResource');

        $resources = $this->resourceService->listToSelect();
        $selectedResource = $selectedResourceId ? $resources->firstWhere('id', $selectedResourceId) : $resources->first();

        return Inertia::render('Index/Dashboard', [
            'resources' => IndexSelect::collection($resources),
            'selectedResource' => new IndexSelect($selectedResource),
            'selectedTab' => $request->input('selectedTab', 0)
        ]);
    }
}
