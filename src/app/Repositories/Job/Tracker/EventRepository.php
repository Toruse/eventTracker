<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Event;

class EventRepository
{
    public function findOne($event)
    {
        return Event::firstWhere('name', $event);
    }
}
