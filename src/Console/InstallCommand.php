<?php

namespace Nadi\CakePHP\Console;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Nadi\CakePHP\Shipper\Shipper;
use Nadi\Shipper\Exceptions\ShipperException;
use Nadi\Shipper\Exceptions\UnsupportedPlatformException;

class InstallCommand extends Command
{
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Install Nadi for CakePHP');
        $parser->addOption('skip-shipper', [
            'boolean' => true,
            'help' => 'Skip shipper binary installation',
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $io->out('Installing Nadi for CakePHP...');

        if (! $args->getOption('skip-shipper')) {
            $this->installShipper($io);
        }

        $io->success('Successfully installed Nadi');

        return self::CODE_SUCCESS;
    }

    private function installShipper(ConsoleIo $io): void
    {
        $io->out('Installing shipper binary...');

        try {
            $shipper = new Shipper;

            if ($shipper->isInstalled()) {
                $version = $shipper->getInstalledVersion() ?? 'unknown';
                $io->out("Shipper binary already installed (version: {$version})");

                return;
            }

            $binaryPath = $shipper->install();
            $version = $shipper->getInstalledVersion() ?? 'unknown';
            $io->success("Shipper binary installed successfully (version: {$version})");
            $io->out("Binary location: {$binaryPath}");
        } catch (UnsupportedPlatformException $e) {
            $io->warning('Shipper installation skipped: '.$e->getMessage());
        } catch (ShipperException $e) {
            $io->error('Failed to install shipper: '.$e->getMessage());
        }
    }
}
