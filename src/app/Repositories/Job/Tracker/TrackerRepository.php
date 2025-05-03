<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Tracker;

class TrackerRepository
{
    public function existsSessionName($visitorId, $sessionName) {
        return Tracker::where([
            ['visitor_id', '=', $visitorId],
            ['session_name', '=', $sessionName],
        ])->exists();
    }

    public function create($data)
    {
        return Tracker::create($data);
    }
}
