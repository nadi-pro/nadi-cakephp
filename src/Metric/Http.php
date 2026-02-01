<?php

namespace Nadi\CakePHP\Metric;

use Nadi\CakePHP\Support\OpenTelemetrySemanticConventions;
use Nadi\Metric\Base;

class Http extends Base
{
    public function metrics(): array
    {
        if (PHP_SAPI === 'cli' || ! isset($_SERVER['REQUEST_URI'])) {
            return [];
        }

        $metrics = OpenTelemetrySemanticConventions::httpAttributesFromGlobals();

        $startTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;
        if ($startTime) {
            $metrics[OpenTelemetrySemanticConventions::HTTP_CLIENT_DURATION] = floor((microtime(true) - $startTime) * 1000);
        }

        return $metrics;
    }
}
