<?php

namespace App\Services\Job\Tracker;

use App\Repositories\Job\Tracker\StatisticCountryRepository;
use Illuminate\Support\Carbon;

class StatisticCountryService
{
    protected $statisticCountryRepository;

    public function __construct() {
        $this->statisticCountryRepository = new StatisticCountryRepository();
    }

    public function incrementDayVisits($resource, $country, $visits) {
        $this->statisticCountryRepository->incrementDayVisitsOrCreate([
            [
                'resource_id' => $resource->id,
                'user_id' => $resource->user_id,
                'country' => $country,
                'date_visits' => Carbon::today(),
            ],
            $visits
        ]);
    }
}
