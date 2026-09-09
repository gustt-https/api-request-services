<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Force-notify worker user IDs (local / testing only)
    |--------------------------------------------------------------------------
    |
    | Comma-separated user IDs always included when notifying nearby workers,
    | even outside the radius. Empty in production. Example: "6" or "6,4".
    |
    */
    'force_notify_worker_ids' => array_values(array_filter(array_map(
        'intval',
        explode(',', (string) env('DEV_FORCE_NOTIFY_WORKER_IDS', '')),
    ))),

];
