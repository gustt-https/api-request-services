<?php

namespace App\Jobs;

use App\Models\RequestService;
use App\Service\V1\firebase\FirebaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


class NotifyWorkersOfNewRequest implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $request){}

    /**
     * Execute the job.
     */
    public function handle(FirebaseService $firebase): void
    {   
        
    }
}
