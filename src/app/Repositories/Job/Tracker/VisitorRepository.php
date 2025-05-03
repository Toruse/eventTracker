<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\Visitor;

class VisitorRepository
{
    public function existsCode($visitorId) {
        return Visitor::where('code', $visitorId)->exists();
    }

    public function updateOrCreate($visitor_id, array $attributes)
    {
        return Visitor::updateOrCreate([
            'code' => $visitor_id
        ], $attributes);
    }
}
