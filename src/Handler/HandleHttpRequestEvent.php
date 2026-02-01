<?php

namespace Nadi\CakePHP\Handler;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Nadi\CakePHP\Data\Entry;
use Nadi\CakePHP\Support\OpenTelemetrySemanticConventions;
use Nadi\Data\Type;
use Psr\Http\Message\ResponseInterface;

class HandleHttpRequestEvent extends Base
{
    public function handle(ServerRequest $request, ResponseInterface $response, float $startTime): void
    {
        $statusCode = $response->getStatusCode();
        $ignoredCodes = Configure::read('Nadi.http.ignored_status_codes', []);

        if (in_array($statusCode, $ignoredCodes)) {
            return;
        }

        $uri = (string) $request->getUri();
        $method = $request->getMethod();
        $title = "$uri returned HTTP Status Code $statusCode";

        $otelAttributes = OpenTelemetrySemanticConventions::httpAttributesFromRequest($request, $response);
        $userAttributes = OpenTelemetrySemanticConventions::userAttributes();
        $sessionAttributes = OpenTelemetrySemanticConventions::sessionAttributes();
        $performanceAttributes = OpenTelemetrySemanticConventions::performanceAttributes($startTime, memory_get_peak_usage(true));

        $otelData = array_merge($otelAttributes, $userAttributes, $sessionAttributes, $performanceAttributes);

        $entryData = [
            'title' => $title,
            'description' => "$uri for $method request returned HTTP Status Code $statusCode",
            'uri' => $uri,
            'method' => $method,
            'controller_action' => $request->getParam('controller').'@'.$request->getParam('action'),
            'headers' => $this->headers($request),
            'payload' => $this->payload($request),
            'response_status' => $statusCode,
            'response' => $this->formatResponse($response),
            'duration' => floor((microtime(true) - $startTime) * 1000),
            'memory' => round(memory_get_peak_usage(true) / 1024 / 1025, 1),
            'otel' => $otelData,
        ];

        $this->store(Entry::make(
            Type::HTTP,
            $entryData
        )->setHashFamily(
            $this->hash($method.$statusCode.$uri.date('Y-m-d H'))
        )->tags($this->generateTags($request, $response))->toArray());
    }

    protected function generateTags(ServerRequest $request, ResponseInterface $response): array
    {
        $tags = [
            $request->getMethod(),
            $response->getStatusCode(),
            'http.method:'.$request->getMethod(),
            'http.status_code:'.$response->getStatusCode(),
        ];

        if ($controller = $request->getParam('controller')) {
            $tags[] = 'cakephp.controller:'.$controller;
        }

        if ($action = $request->getParam('action')) {
            $tags[] = 'cakephp.action:'.$action;
        }

        return $tags;
    }

    protected function headers(ServerRequest $request): array
    {
        $headers = [];
        $hiddenHeaders = Configure::read('Nadi.http.hidden_request_headers', []);

        foreach ($request->getHeaders() as $name => $values) {
            $headerValue = $values[0] ?? '';
            if (in_array(strtolower($name), $hiddenHeaders)) {
                $headerValue = '********';
            }
            $headers[$name] = $headerValue;
        }

        return $headers;
    }

    protected function payload(ServerRequest $request): array
    {
        $data = (array) $request->getParsedBody();
        $hiddenParams = Configure::read('Nadi.http.hidden_parameters', []);

        foreach ($hiddenParams as $param) {
            if (isset($data[$param])) {
                $data[$param] = '********';
            }
        }

        return $data;
    }

    protected function formatResponse(ResponseInterface $response): string
    {
        $body = (string) $response->getBody();

        if (strlen($body) > 64000) {
            return 'Purged By Nadi';
        }

        $decoded = json_decode($body, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decoded);
        }

        $contentType = $response->getHeaderLine('Content-Type');
        if (str_starts_with($contentType, 'text/plain')) {
            return $body;
        }

        return 'HTML Response';
    }
}
