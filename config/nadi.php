<?php

return [
    'Nadi' => [
        'enabled' => env('NADI_ENABLED', true),
        'driver' => env('NADI_DRIVER', 'log'),
        'connections' => [
            'log' => [
                'path' => env('NADI_STORAGE_PATH', (defined('TMP') ? TMP : sys_get_temp_dir()).'/nadi'),
            ],
            'http' => [
                'apiKey' => env('NADI_API_KEY'),
                'appKey' => env('NADI_APP_KEY'),
                'endpoint' => env('NADI_ENDPOINT', 'https://nadi.pro/api'),
                'version' => env('NADI_API_VERSION', 'v1'),
            ],
            'opentelemetry' => [
                'endpoint' => env('NADI_OTEL_ENDPOINT', 'http://localhost:4318'),
                'service_name' => env('NADI_OTEL_SERVICE_NAME', 'cakephp-app'),
                'service_version' => env('NADI_OTEL_SERVICE_VERSION', '1.0.0'),
                'deployment_environment' => env('NADI_OTEL_DEPLOYMENT_ENVIRONMENT', 'production'),
                'suppress_errors' => env('NADI_OTEL_SUPPRESS_ERRORS', true),
            ],
        ],
        'query' => [
            'slow_threshold' => env('NADI_QUERY_SLOW_THRESHOLD', 500),
        ],
        'http' => [
            'hidden_request_headers' => [
                'authorization',
                'php-auth-pw',
            ],
            'hidden_parameters' => [
                'password',
                'password_confirmation',
            ],
            'ignored_status_codes' => [
                100, 101, 102, 103,
                200, 201, 202, 203, 204, 205, 206, 207,
                300, 302, 303, 304, 305, 306, 307, 308,
            ],
        ],
        'sampling' => [
            'strategy' => env('NADI_SAMPLING_STRATEGY', 'fixed_rate'),
            'config' => [
                'sampling_rate' => env('NADI_SAMPLING_RATE', 0.1),
                'base_rate' => env('NADI_SAMPLING_BASE_RATE', 0.05),
                'load_factor' => env('NADI_SAMPLING_LOAD_FACTOR', 1.0),
                'interval_seconds' => env('NADI_SAMPLING_INTERVAL_SECONDS', 60),
            ],
            'strategies' => [
                'dynamic_rate' => \Nadi\Sampling\DynamicRateSampling::class,
                'fixed_rate' => \Nadi\Sampling\FixedRateSampling::class,
                'interval' => \Nadi\Sampling\IntervalSampling::class,
                'peak_load' => \Nadi\Sampling\PeakLoadSampling::class,
            ],
        ],
    ],
];
