# Transport Drivers

The Nadi CakePHP SDK supports multiple transport drivers for sending monitoring data.
Set the driver via the `NADI_DRIVER` environment variable.

## Available Drivers

| Driver | Value | Description |
|--------|-------|-------------|
| Log | `log` | Writes data to local disk (default) |
| HTTP | `http` | Sends data to the Nadi API |
| OpenTelemetry | `opentelemetry` | Exports data via OpenTelemetry protocol |

## Log Driver

Writes monitoring data to a local directory. Useful for development and debugging.

```bash
NADI_DRIVER=log
NADI_STORAGE_PATH=/tmp/nadi
```

| Option | Environment Variable | Default |
|--------|---------------------|---------|
| Storage path | `NADI_STORAGE_PATH` | `TMP/nadi` (CakePHP temp directory) |

Configuration in `config/nadi.php`:

```php
'connections' => [
    'log' => [
        'path' => env('NADI_STORAGE_PATH', TMP . '/nadi'),
    ],
],
```

## HTTP Driver

Sends monitoring data to the Nadi API. This is the primary driver for production use.

```bash
NADI_DRIVER=http
NADI_API_KEY=your-api-key
NADI_APP_KEY=your-app-key
NADI_ENDPOINT=https://api.nadi.pro
```

| Option | Environment Variable | Default |
|--------|---------------------|---------|
| API key | `NADI_API_KEY` | (none, required) |
| App key | `NADI_APP_KEY` | (none, required) |
| Endpoint | `NADI_ENDPOINT` | `https://api.nadi.pro` |
| API version | `NADI_API_VERSION` | `v1` |

Configuration in `config/nadi.php`:

```php
'connections' => [
    'http' => [
        'apiKey' => env('NADI_API_KEY'),
        'appKey' => env('NADI_APP_KEY'),
        'endpoint' => env('NADI_ENDPOINT', 'https://api.nadi.pro'),
        'version' => env('NADI_API_VERSION', 'v1'),
    ],
],
```

## OpenTelemetry Driver

Exports monitoring data via the OpenTelemetry protocol (OTLP). When this driver is active,
the `OpenTelemetryMiddleware` is automatically added to the middleware queue.

```bash
NADI_DRIVER=opentelemetry
NADI_OTEL_ENDPOINT=http://localhost:4318
NADI_OTEL_SERVICE_NAME=my-cakephp-app
```

| Option | Environment Variable | Default |
|--------|---------------------|---------|
| Endpoint | `NADI_OTEL_ENDPOINT` | `http://localhost:4318` |
| Service name | `NADI_OTEL_SERVICE_NAME` | `cakephp-app` |
| Service version | `NADI_OTEL_SERVICE_VERSION` | `1.0.0` |
| Environment | `NADI_OTEL_DEPLOYMENT_ENVIRONMENT` | `production` |
| Suppress errors | `NADI_OTEL_SUPPRESS_ERRORS` | `true` |

Configuration in `config/nadi.php`:

```php
'connections' => [
    'opentelemetry' => [
        'endpoint' => env('NADI_OTEL_ENDPOINT', 'http://localhost:4318'),
        'service_name' => env('NADI_OTEL_SERVICE_NAME', 'cakephp-app'),
        'service_version' => env('NADI_OTEL_SERVICE_VERSION', '1.0.0'),
        'deployment_environment' => env('NADI_OTEL_DEPLOYMENT_ENVIRONMENT', 'production'),
        'suppress_errors' => env('NADI_OTEL_SUPPRESS_ERRORS', true),
    ],
],
```

## Next Steps

- [Sampling Strategies](02-sampling.md) - Control data volume
- [Environment Variables](03-environment-variables.md) - Complete variable reference
