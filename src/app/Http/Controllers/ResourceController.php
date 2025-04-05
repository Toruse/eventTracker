<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tracker\Resource\CreateRequest;
use App\Http\Requests\Tracker\Resource\EditRequest;
use App\Http\Resources\Tracker\Resource\EditResource;
use App\Services\Tracker\ResourceService;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function __construct(
        protected ResourceService $resourceService,
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Resource/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $this->resourceService->create($request->only(['domain']));

        return redirect()->route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resource = $this->resourceService->findOwnedById($id);

        return inertia('Resource/Edit', [
            'resource' => new EditResource($resource)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditRequest $request, string $id)
    {
        $resource = $this->resourceService->findOwnedById($id);

        $this->resourceService->update($resource, $request->only(['domain']));

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = $this->resourceService->findOwnedById($id);
        $this->resourceService->delete($model);
        return redirect()->route('dashboard');
    }
}
