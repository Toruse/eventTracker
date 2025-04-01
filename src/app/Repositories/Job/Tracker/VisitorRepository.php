<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Visitor;

class VisitorRepository
{
    public function get($visitor_id)
    {
        return Visitor::firstOrCreate([
            'code' => $visitor_id
        ]);
    }
}
