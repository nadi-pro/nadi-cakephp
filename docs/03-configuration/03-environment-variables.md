# Environment Variables

Complete reference of all environment variables supported by the Nadi CakePHP SDK.

## General

| Variable | Default | Description |
|----------|---------|-------------|
| `NADI_ENABLED` | `true` | Enable or disable Nadi monitoring |
| `NADI_DRIVER` | `log` | Transport driver: `log`, `http`, or `opentelemetry` |

## HTTP Driver

| Variable | Default | Description |
|----------|---------|-------------|
| `NADI_API_KEY` | (none) | API key for Nadi platform authentication |
| `NADI_APP_KEY` | (none) | Application key for Nadi platform |
| `NADI_ENDPOINT` | `https://api.nadi.pro` | Nadi API endpoint URL |
| `NADI_API_VERSION` | `v1` | API version |

## Log Driver

| Variable | Default | Description |
|----------|---------|-------------|
| `NADI_STORAGE_PATH` | `TMP/nadi` | Local directory for log driver output |

## OpenTelemetry Driver

| Variable | Default | Description |
|----------|---------|-------------|
| `NADI_OTEL_ENDPOINT` | `http://localhost:4318` | OTLP collector endpoint |
| `NADI_OTEL_SERVICE_NAME` | `cakephp-app` | Service name for traces |
| `NADI_OTEL_SERVICE_VERSION` | `1.0.0` | Service version for traces |
| `NADI_OTEL_DEPLOYMENT_ENVIRONMENT` | `production` | Deployment environment name |
| `NADI_OTEL_SUPPRESS_ERRORS` | `true` | Suppress OpenTelemetry errors silently |

## Query Monitoring

| Variable | Default | Description |
|----------|---------|-------------|
| `NADI_QUERY_SLOW_THRESHOLD` | `500` | Slow query threshold in milliseconds |

## Sampling

| Variable | Default | Description |
|----------|---------|-------------|
| `NADI_SAMPLING_STRATEGY` | `fixed_rate` | Sampling strategy name |
| `NADI_SAMPLING_RATE` | `0.1` | Fixed rate sampling percentage (0.0 - 1.0) |
| `NADI_SAMPLING_BASE_RATE` | `0.05` | Base rate for dynamic sampling |
| `NADI_SAMPLING_LOAD_FACTOR` | `1.0` | Load factor multiplier |
| `NADI_SAMPLING_INTERVAL_SECONDS` | `60` | Interval between samples (seconds) |

## Example `.env` File

```bash
# Nadi Monitoring
NADI_ENABLED=true
NADI_DRIVER=http
NADI_API_KEY=your-api-key
NADI_APP_KEY=your-app-key

# Optional overrides
NADI_QUERY_SLOW_THRESHOLD=1000
NADI_SAMPLING_STRATEGY=fixed_rate
NADI_SAMPLING_RATE=0.1
```

## Next Steps

- [Drivers](01-drivers.md) - Detailed driver configuration
- [Sampling](02-sampling.md) - Sampling strategy details
