<?php

namespace Nadi\CakePHP\Data;

use Nadi\CakePHP\Concerns\InteractsWithMetric;
use Nadi\Data\ExceptionEntry as DataExceptionEntry;

class ExceptionEntry extends DataExceptionEntry
{
    use InteractsWithMetric;

    public function __construct($exception, $type, array $content)
    {
        parent::__construct($exception, $type, $content);

        $this->registerMetrics();
    }
}
