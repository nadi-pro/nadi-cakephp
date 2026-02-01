<?php

namespace Nadi\CakePHP\Metric;

use Cake\Core\Configure;
use Nadi\Metric\Base;

class Application extends Base
{
    public function metrics(): array
    {
        $metrics = [
            'app.environment' => Configure::read('debug') ? 'debug' : 'production',
        ];

        if (PHP_SAPI !== 'cli' && isset($_SERVER['REQUEST_URI'])) {
            if (defined('ROOT')) {
                $metrics['app.root'] = ROOT;
            }
        } else {
            $metrics['app.context'] = 'console';

            if (isset($_SERVER['argv'])) {
                $metrics['app.command'] = implode(' ', array_slice($_SERVER['argv'], 1));
            }
        }

        return $metrics;
    }
}
