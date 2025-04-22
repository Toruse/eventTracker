<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Visitor;

class VisitorRepository
{
    public function getOrCreate($visitor_id, array $attributes)
    {
        return Visitor::firstOrCreate([
            'code' => $visitor_id
        ], $attributes);
    }
}
