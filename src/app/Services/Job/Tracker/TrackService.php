<?php

namespace App\Services\Job\Tracker;

use App\Repositories\Job\Tracker\EventRepository;
use App\Repositories\Job\Tracker\ResourceRepository;
use App\Repositories\Job\Tracker\TrackerRepository;
use App\Repositories\Job\Tracker\VisitorRepository;
use Illuminate\Support\Carbon;

class TrackService {

    protected $visitorRepository;
    protected $eventRepository;

    public function __construct() {
        $this->visitorRepository = new VisitorRepository();
        $this->eventRepository = new EventRepository();
        $this->resourceRepository = new ResourceRepository();
        $this->trackerRepository = new TrackerRepository();
    }

    public function saveEvent($data) {
        $resource = $this->resourceRepository->findOne($data['id']);
        if (!$resource) {
            return false;
        }

        if ($data['ht'] != $resource->domain) {
            return false;
        }

        $data['id'] = $resource->id;

        $visitorId = null;
        if ($data['vi']) {
            $visitor = $this->visitorRepository->getOrCreate($data['vi'], $data['sr']);
            $visitorId = $visitor->id;
        }
        $data['vi'] = $visitorId;
        $event = $this->eventRepository->findOne($data['tp']);
        if (!$event) {
            return false;
        }
        $data['tp'] = $event->id;

        if (empty($data['tm'])) {
            return false;
        }
        $data['tm'] = Carbon::parse($data['tm'])->toDateTimeString();

        return $this->insertEvent($data);
    }

    public function insertEvent($data) {
        return $this->trackerRepository->create([
            'hostname' => $data['ht'],
            'href' => $data['hf'],
            'event_id' => $data['tp'],
            'session_name' => $data['si'],
            'resource_id' => $data['id'],
            'visitor_id' => $data['vi'],
            'time_event' => $data['tm'],
            'target' => $data['xp'] ?? null,
            'target_id' => $data['ti'] ?? null,
            'mouse_x' => $data['ms']['x'] ?? 0,
            'mouse_y' => $data['ms']['y'] ?? 0,
            'browser_w' => $data['br']['w'] ?? 0,
            'browser_h' => $data['br']['h'] ?? 0,
            'event_data' => $data['dt'] ?? [],
        ]);
    }
}
