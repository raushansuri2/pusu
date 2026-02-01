<?php

return [
    'Queue' => [
        'workermaxruntime' => 60, // Maximum runtime for a worker in seconds
        'sleeptime' => 10,         // Sleep time between job checks in seconds
        'retry' => 3,              // Number of retries for failed jobs
        'maxworkers' => 1,         // Maximum number of workers
    ],
];