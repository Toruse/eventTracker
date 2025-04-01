<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Resource;

class ResourceRepository
{
    public function findOne($event)
    {
        return Resource::firstWhere('code', $event);
    }
}
