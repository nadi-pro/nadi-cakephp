<?php

namespace Nadi\CakePHP\Support;

use Cake\Http\ServerRequest;
use Nadi\Support\OpenTelemetrySemanticConventions as CoreConventions;
use Psr\Http\Message\ResponseInterface;

class OpenTelemetrySemanticConventions extends CoreConventions
{
    public const CAKEPHP_CONTROLLER = 'cakephp.controller';

    public const CAKEPHP_ACTION = 'cakephp.action';

    public const CAKEPHP_PLUGIN = 'cakephp.plugin';

    public const CAKEPHP_ROUTE_NAME = 'cakephp.route.name';

    public const DB_CONNECTION_NAME = 'db.connection.name';

    public const HTTP_CLIENT_DURATION = 'http.client.duration';

    public const HTTP_QUERY = 'http.query';

    public const HTTP_HEADERS = 'http.headers';

    public static function httpAttributesFromRequest(ServerRequest $request, ?ResponseInterface $response = null): array
    {
        $attributes = [
            self::HTTP_METHOD => $request->getMethod(),
            self::HTTP_URL => (string) $request->getUri(),
            self::HTTP_SCHEME => $request->getUri()->getScheme(),
            self::HTTP_HOST => $request->getUri()->getHost(),
            self::HTTP_TARGET => $request->getRequestTarget(),
        ];

        if ($userAgent = $request->getHeaderLine('User-Agent')) {
            $attributes[self::HTTP_USER_AGENT] = $userAgent;
        }

        if ($controller = $request->getParam('controller')) {
            $attributes[self::CAKEPHP_CONTROLLER] = $controller;
        }

        if ($action = $request->getParam('action')) {
            $attributes[self::CAKEPHP_ACTION] = $action;
        }

        if ($plugin = $request->getParam('plugin')) {
            $attributes[self::CAKEPHP_PLUGIN] = $plugin;
        }

        if ($clientIp = $request->clientIp()) {
            $attributes[self::HTTP_CLIENT_IP] = $clientIp;
        }

        if ($response) {
            $attributes[self::HTTP_STATUS_CODE] = $response->getStatusCode();
        }

        return $attributes;
    }

    public static function httpAttributesFromGlobals(): array
    {
        $attributes = [];

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $attributes[self::HTTP_METHOD] = $_SERVER['REQUEST_METHOD'];
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
            $attributes[self::HTTP_URL] = $scheme.'://'.$host.$_SERVER['REQUEST_URI'];
            $attributes[self::HTTP_SCHEME] = $scheme;
            $attributes[self::HTTP_HOST] = $host;
            $attributes[self::HTTP_TARGET] = $_SERVER['REQUEST_URI'];
        }

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $attributes[self::HTTP_USER_AGENT] = $_SERVER['HTTP_USER_AGENT'];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $attributes[self::HTTP_CLIENT_IP] = $_SERVER['REMOTE_ADDR'];
        }

        return $attributes;
    }

    public static function databaseAttributes(string $connectionName, string $query, float $duration): array
    {
        $attributes = [
            self::DB_SYSTEM => 'unknown',
            self::DB_STATEMENT => $query,
            self::DB_QUERY_DURATION => $duration,
        ];

        if (preg_match('/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|DROP|ALTER|TRUNCATE)\s+/i', $query, $matches)) {
            $attributes[self::DB_OPERATION] = strtoupper($matches[1]);
        }

        if (preg_match('/(?:FROM|INTO|UPDATE|TABLE)\s+`?(\w+)`?/i', $query, $matches)) {
            $attributes[self::DB_SQL_TABLE] = $matches[1];
        }

        return $attributes;
    }

    public static function userAttributes(): array
    {
        return [];
    }

    public static function sessionAttributes(): array
    {
        $attributes = [];

        if (session_status() === PHP_SESSION_ACTIVE && session_id()) {
            $attributes[self::SESSION_ID] = session_id();
        }

        return $attributes;
    }

    public static function exceptionAttributes(\Throwable $exception): array
    {
        return parent::exceptionAttributes($exception);
    }

    public static function performanceAttributes(float $startTime, ?int $memoryPeak = null): array
    {
        return parent::performanceAttributes($startTime, $memoryPeak);
    }
}
