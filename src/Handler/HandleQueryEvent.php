<?php

namespace Nadi\CakePHP\Handler;

use Cake\Core\Configure;
use Nadi\CakePHP\Concerns\FetchesStackTrace;
use Nadi\CakePHP\Data\Entry;
use Nadi\CakePHP\Support\OpenTelemetrySemanticConventions;
use Nadi\Data\Type;

class HandleQueryEvent extends Base
{
    use FetchesStackTrace;

    public function handle(string $sql, float $time, string $connectionName = 'default'): void
    {
        $slowThreshold = Configure::read('Nadi.query.slow_threshold', 500);

        if ($time <= $slowThreshold) {
            return;
        }

        $otelAttributes = OpenTelemetrySemanticConventions::databaseAttributes($connectionName, $sql, $time);
        $userAttributes = OpenTelemetrySemanticConventions::userAttributes();
        $sessionAttributes = OpenTelemetrySemanticConventions::sessionAttributes();
        $otelData = array_merge($otelAttributes, $userAttributes, $sessionAttributes);

        if ($caller = $this->getCallerFromStackTrace()) {
            $otelData[OpenTelemetrySemanticConventions::CODE_FILEPATH] = $caller['file'];
            $otelData[OpenTelemetrySemanticConventions::CODE_LINENO] = $caller['line'];

            $entryData = [
                'connection' => $connectionName,
                'sql' => $sql,
                'time' => number_format($time, 2, '.', ''),
                'slow' => true,
                'file' => $caller['file'],
                'line' => $caller['line'],
                'otel' => $otelData,
            ];

            $this->store(
                Entry::make(Type::QUERY, $entryData)
                    ->setHashFamily($this->hash($sql.date('Y-m-d')))
                    ->tags($this->tags($sql, $time, $connectionName))
                    ->toArray()
            );
        }
    }

    protected function tags(string $sql, float $time, string $connectionName): array
    {
        $tags = ['slow'];

        $tags[] = OpenTelemetrySemanticConventions::DB_CONNECTION_NAME.':'.$connectionName;

        if (preg_match('/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|DROP|ALTER|TRUNCATE)\s+/i', $sql, $matches)) {
            $tags[] = OpenTelemetrySemanticConventions::DB_OPERATION.':'.strtoupper($matches[1]);
        }

        $tags[] = 'query.slow:true';

        return $tags;
    }
}
