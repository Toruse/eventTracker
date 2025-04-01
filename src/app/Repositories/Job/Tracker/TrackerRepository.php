<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Tracker;

class TrackerRepository
{
    public function create($data)
    {
        return Tracker::create($data);
    }
}
