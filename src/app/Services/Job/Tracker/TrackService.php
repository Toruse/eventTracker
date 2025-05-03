<?php

namespace App\Services\Job\Tracker;

use App\Repositories\Job\Tracker\EventRepository;
use App\Repositories\Job\Tracker\ResourceRepository;
use App\Repositories\Job\Tracker\TrackerRepository;
use App\Repositories\Job\Tracker\VisitorRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrackService {

    protected $visitorRepository;
    protected $eventRepository;
    protected $resourceRepository;
    protected $trackerRepository;
    protected $statisticCountryService;

    public function __construct() {
        $this->visitorRepository = new VisitorRepository();
        $this->eventRepository = new EventRepository();
        $this->resourceRepository = new ResourceRepository();
        $this->trackerRepository = new TrackerRepository();
        $this->statisticCountryService = new StatisticCountryService();
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

        $statisticCountry = [];

        $visitorId = null;
        if ($data['vi']) {
            if (!$this->visitorRepository->existsCode($data['vi'])) {
                $statisticCountry['unique_visits'] = DB::raw('unique_visits + 1');
            }
            $visitor = $this->visitorRepository->updateOrCreate($data['vi'], $data['sr']);
            $visitorId = $visitor->id;
        }
        $data['vi'] = $visitorId;

        if (!$this->trackerRepository->existsSessionName($visitorId, $data['si'])) {
            $statisticCountry['visits'] = DB::raw('visits + 1');
        }

        $event = $this->eventRepository->findOne($data['tp']);
        if (!$event) {
            return false;
        }
        $data['tp'] = $event->id;

        if (empty($data['tm'])) {
            return false;
        }
        $data['tm'] = Carbon::parse($data['tm'])->toDateTimeString();

        $statisticCountry['events'] = DB::raw('events + 1');

        $this->statisticCountryService->incrementDayVisits($resource, $visitor->country, $statisticCountry);

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
