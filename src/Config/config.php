<?php

return [
    'hosts' => env('ELASTICSEARCH_HOSTS', 'localhost'),
    'retries' => env('ELASTICSEARCH_RETRIES', 3),
    'logger' => null,
    'connection' => [
        'pool' => null,
        'selector' => null
    ],
    'serializer' => null
];
