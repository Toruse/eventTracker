<?php

namespace App\Repositories\Job\Tracker;

use App\Models\Tracker\StatisticCountry;

class StatisticCountryRepository
{
    public function incrementDayVisitsOrCreate(array $data) {
        StatisticCountry::updateOrInsert($data[0], $data[1]);
    }
}
