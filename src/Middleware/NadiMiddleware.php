<?php

namespace Nadi\CakePHP\Middleware;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Nadi\CakePHP\Handler\HandleHttpRequestEvent;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NadiMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $startTime = microtime(true);

        $response = $handler->handle($request);

        if (! Configure::read('Nadi.enabled', true)) {
            return $response;
        }

        try {
            $httpHandler = new HandleHttpRequestEvent;

            if ($request instanceof ServerRequest) {
                $httpHandler->handle($request, $response, $startTime);
            }
        } catch (\Throwable $e) {
            // Silently ignore monitoring errors
        }

        return $response;
    }
}
