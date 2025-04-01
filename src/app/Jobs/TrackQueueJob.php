<?php

namespace App\Jobs;

use App\Services\Job\Tracker\TrackService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $trackService;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->trackService = new TrackService();
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->trackService->saveEvent($this->data)) {
            echo $this->data['id'] . ' ' . $this->data['tm'] . ' Ok';
        } else {
            echo $this->data['id'] . ' ' . $this->data['tm'] . ' Error';
        }
    }
}
