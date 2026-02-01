<?php

namespace Nadi\CakePHP\Middleware;

use Cake\Core\Configure;
use Nadi\CakePHP\Support\OpenTelemetrySemanticConventions;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\Context\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OpenTelemetryMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (Configure::read('Nadi.driver') !== 'opentelemetry') {
            return $handler->handle($request);
        }

        try {
            $carrier = [];
            foreach ($request->getHeaders() as $name => $values) {
                $carrier[strtolower($name)] = $values[0] ?? '';
            }

            $context = TraceContextPropagator::getInstance()->extract($carrier);

            $spanName = $request->getMethod().' '.$request->getUri()->getPath();

            $tracer = \OpenTelemetry\API\Globals::tracerProvider()->getTracer('nadi-cakephp');
            $span = $tracer->spanBuilder($spanName)
                ->setSpanKind(SpanKind::KIND_SERVER)
                ->setParent($context)
                ->startSpan();

            $scope = $span->activate();

            try {
                $response = $handler->handle($request);

                $span->setAttribute(OpenTelemetrySemanticConventions::HTTP_STATUS_CODE, $response->getStatusCode());

                if ($response->getStatusCode() >= 400) {
                    $span->setStatus(StatusCode::STATUS_ERROR, 'HTTP '.$response->getStatusCode());
                } else {
                    $span->setStatus(StatusCode::STATUS_OK);
                }

                $responseCarrier = [];
                TraceContextPropagator::getInstance()->inject($responseCarrier, null, Context::getCurrent());
                foreach ($responseCarrier as $name => $value) {
                    $response = $response->withHeader($name, $value);
                }

                return $response;
            } catch (\Throwable $exception) {
                $span->recordException($exception);
                $span->setStatus(StatusCode::STATUS_ERROR, $exception->getMessage());

                throw $exception;
            } finally {
                $span->end();
                $scope->detach();
            }
        } catch (\Throwable $e) {
            return $handler->handle($request);
        }
    }
}
