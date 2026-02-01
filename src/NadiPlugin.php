<?php

namespace Nadi\CakePHP;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Nadi\CakePHP\Console\InstallCommand;
use Nadi\CakePHP\Console\TestCommand;
use Nadi\CakePHP\Console\UpdateShipperCommand;
use Nadi\CakePHP\Console\VerifyCommand;
use Nadi\CakePHP\Middleware\NadiMiddleware;
use Nadi\CakePHP\Middleware\OpenTelemetryMiddleware;

class NadiPlugin extends BasePlugin
{
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        if (! Configure::check('Nadi')) {
            Configure::load('nadi');
        }

        if (! Configure::read('Nadi.enabled', true)) {
            return;
        }

        Nadi::setInstance(Transporter::make());

        $this->registerExceptionHandler();
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        if (! Configure::read('Nadi.enabled', true)) {
            return $middlewareQueue;
        }

        $middlewareQueue->add(new NadiMiddleware);

        if (Configure::read('Nadi.driver') === 'opentelemetry') {
            $middlewareQueue->add(new OpenTelemetryMiddleware);
        }

        return $middlewareQueue;
    }

    public function console(CommandCollection $commands): CommandCollection
    {
        $commands = parent::console($commands);

        $commands->add('nadi:install', InstallCommand::class);
        $commands->add('nadi:test', TestCommand::class);
        $commands->add('nadi:verify', VerifyCommand::class);
        $commands->add('nadi:update-shipper', UpdateShipperCommand::class);

        return $commands;
    }

    private function registerExceptionHandler(): void
    {
        $previousHandler = set_exception_handler(null);
        restore_exception_handler();

        set_exception_handler(function (\Throwable $exception) use ($previousHandler) {
            try {
                $handler = new Handler\HandleExceptionEvent;
                $handler->handle($exception);
            } catch (\Throwable $e) {
                // Silently ignore monitoring errors
            }

            if ($previousHandler) {
                $previousHandler($exception);
            }
        });
    }
}
