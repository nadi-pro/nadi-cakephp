<?php

namespace Nadi\CakePHP\Metric;

use Cake\Core\Configure;
use Nadi\CakePHP\Support\OpenTelemetrySemanticConventions;
use Nadi\Metric\Base;

class Framework extends Base
{
    public function metrics(): array
    {
        return [
            'framework.name' => 'cakephp',
            'framework.version' => Configure::version(),
            OpenTelemetrySemanticConventions::SERVICE_NAME => Configure::read('Nadi.connections.opentelemetry.service_name', Configure::read('App.name', 'cakephp-app')),
            OpenTelemetrySemanticConventions::SERVICE_VERSION => Configure::read('Nadi.connections.opentelemetry.service_version', '1.0.0'),
            OpenTelemetrySemanticConventions::DEPLOYMENT_ENVIRONMENT => Configure::read('Nadi.connections.opentelemetry.deployment_environment', Configure::read('debug') ? 'debug' : 'production'),
        ];
    }
}
