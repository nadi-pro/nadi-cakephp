<?php

namespace Nadi\CakePHP\Console;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Nadi\CakePHP\Nadi;

class VerifyCommand extends Command
{
    public static function defaultName(): string
    {
        return 'nadi:verify';
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $io->out('Verifying Nadi configuration...');

        $isEnabled = Configure::read('Nadi.enabled', true);
        $driver = Configure::read('Nadi.driver', 'log');

        $io->out('Nadi monitoring: '.($isEnabled ? 'Enabled' : 'Disabled'));
        $io->out("Driver: {$driver}");

        $result = Nadi::verify();

        if ($result) {
            $io->success('Application Verification Status: OK');
        } else {
            $io->error('Application Verification Status: Failed');
        }

        return self::CODE_SUCCESS;
    }
}
