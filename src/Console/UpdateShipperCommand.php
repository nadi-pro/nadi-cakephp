<?php

namespace Nadi\CakePHP\Console;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Nadi\CakePHP\Shipper\Shipper;
use Nadi\Shipper\Exceptions\ShipperException;

class UpdateShipperCommand extends Command
{
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Update the Nadi shipper binary');
        $parser->addOption('force', [
            'boolean' => true,
            'help' => 'Force replace the shipper binary',
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        try {
            $shipper = new Shipper;

            if ($args->getOption('force')) {
                $io->out('Force re-installing shipper binary...');
                $version = $shipper->reInstall();
                $io->success("Shipper binary installed (version: {$version})");

                return self::CODE_SUCCESS;
            }

            if (! $shipper->isInstalled()) {
                $io->warning('Shipper binary is not installed. Run nadi:install first.');

                return self::CODE_ERROR;
            }

            if ($shipper->needsUpdate()) {
                $newVersion = $shipper->update();
                $io->success("Shipper updated to version: {$newVersion}");
            } else {
                $io->out('Shipper is already up to date.');
            }

            return self::CODE_SUCCESS;
        } catch (ShipperException $e) {
            $io->error('Failed: '.$e->getMessage());

            return self::CODE_ERROR;
        }
    }
}
