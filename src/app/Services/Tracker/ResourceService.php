<?php

namespace App\Services\Tracker;

use App\Repositories\Tracker\ResourceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ResourceService
{

    public function __construct(protected ResourceRepository $resourceRepository){}

    public function listToSelect()
    {
        return $this->resourceRepository->listToSelect();
    }

    public function findOwnedById(int $id) {
        return $this->resourceRepository->findOwnedById($id);
    }

    public function create($request)
    {
        $code = $this->genCode();
        while ($this->existsByCode($code)) {
            $code = $this->genCode();
        }

        $this->resourceRepository->create([
            'code' => $code,
            'domain' => $request['domain'],
            'user_id' => Auth::user()->id
        ]);
    }

    public function update($model, $request) {
        return $this->resourceRepository->update($model, $request);
    }

    public function delete($model)
    {
        $model->delete();
    }

    public function existsByCode($path) {
        return $this->resourceRepository->existsByCode($path);
    }

    public function genCode()
    {
        return Str::substrReplace(Str::upper(Str::random(11)), '-', 4, 0);
    }
}
