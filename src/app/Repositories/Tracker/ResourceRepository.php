<?php

namespace App\Repositories\Tracker;

use App\Models\Tracker\Resource;

class ResourceRepository
{
    public function listToSelect()
    {
        return Resource::owner()->orderBy('id')->get();
    }

    public function findOwnedById(int $id) {
        return Resource::where('id', '=', $id)->owner()
            ->firstOrFail();
    }

    public function existsByCode($code) {
        return Resource::where('code', $code)->exists();
    }

    public function create($request)
    {
        return Resource::create($request);
    }

    public function update(Resource $model, $request)
    {
        return $model->update($request);
    }
}
