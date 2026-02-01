<?php

namespace Nadi\CakePHP\Console;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Nadi\CakePHP\Nadi;

class TestCommand extends Command
{
    public static function defaultName(): string
    {
        return 'nadi:test';
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $driver = Configure::read('Nadi.driver', 'log');
        $io->out("Testing Nadi connectivity using driver: {$driver}");

        $isActive = Nadi::test();

        if ($isActive) {
            $io->success('Connectivity to Nadi is: Active');
        } else {
            $io->error('Connectivity to Nadi is: Inactive');
        }

        return self::CODE_SUCCESS;
    }
}
