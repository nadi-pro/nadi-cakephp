<?php

namespace Nadi\CakePHP\Concerns;

use Nadi\CakePHP\Metric\Application;
use Nadi\CakePHP\Metric\Framework;
use Nadi\CakePHP\Metric\Http;
use Nadi\CakePHP\Metric\Network;

trait InteractsWithMetric
{
    public function registerMetrics(): void
    {
        if (method_exists($this, 'addMetric')) {
            $this->addMetric(new Http);
            $this->addMetric(new Framework);
            $this->addMetric(new Application);
            $this->addMetric(new Network);
        }
    }
}
