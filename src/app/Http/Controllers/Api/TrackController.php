<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\TrackQueueJob;
use App\Services\Api\TrackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    public function __construct(protected TrackService $trackService) {}

    public function index(Request $request)
    {
        $data = $request->all();
        if (!is_array($data)) {
            return response()->json(['status' => 'error']);
        }

        $visitorInfo = $this->trackService->getVisitorInfo();

        $result = [];
        foreach ($data as $event) {
            $validator = Validator::make($event, $this->trackService->validationRules);

            if ($validator->fails()) continue;

            $result = $validator->safe()->only($this->trackService->validationOnly);

            $result['sr'] = $visitorInfo;

            TrackQueueJob::dispatch($result);
        }

        return response()->json(['status' => 'ok']);
    }
}
