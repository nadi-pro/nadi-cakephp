# OpenTelemetry Integration

The Nadi CakePHP SDK includes OpenTelemetry support for distributed tracing.
When the `opentelemetry` driver is active, the SDK creates spans, propagates trace context,
and attaches semantic attributes.

## Enabling OpenTelemetry

Set the driver to `opentelemetry`:

```bash
NADI_DRIVER=opentelemetry
NADI_OTEL_ENDPOINT=http://localhost:4318
NADI_OTEL_SERVICE_NAME=my-cakephp-app
```

When this driver is configured, `NadiPlugin` automatically adds `OpenTelemetryMiddleware`
to the middleware queue in addition to `NadiMiddleware`.

## How It Works

### Trace Context Propagation

The `OpenTelemetryMiddleware` extracts incoming trace context from request headers using
the W3C `TraceContextPropagator`. This allows the CakePHP application to participate
in distributed traces started by upstream services.

### Span Creation

For each request, a server span is created:

- **Span name**: `{METHOD} {path}` (e.g., `GET /api/users`)
- **Span kind**: `SERVER`
- **Parent context**: Extracted from incoming `traceparent` header

### Status Codes

| HTTP Status | Span Status |
|-------------|-------------|
| < 400 | `OK` |
| >= 400 | `ERROR` with message `HTTP {code}` |

### Response Headers

The middleware injects trace context headers into the response, allowing downstream services to continue the trace.

### Exception Recording

If an exception occurs during request processing, it is recorded on the span before being re-thrown:

```text
span.recordException(exception)
span.setStatus(ERROR, exception.getMessage())
```

## Semantic Conventions

The SDK defines CakePHP-specific OpenTelemetry semantic conventions in
`OpenTelemetrySemanticConventions`. These extend the standard OTel conventions
with attributes specific to CakePHP:

- Exception attributes (type, message, stacktrace)
- HTTP attributes (method, status code, URL, user agent)
- Database attributes (connection, query, duration)
- User and session attributes
- Performance attributes (duration, memory)

## Graceful Degradation

If OpenTelemetry packages are not installed or the collector is unavailable, the middleware
falls back to processing the request normally without tracing. Errors in the OTel pipeline
are silently caught.

## Next Steps

- [Drivers](../03-configuration/01-drivers.md) - Driver configuration reference
- [Architecture](../02-architecture/01-overview.md) - How OTel fits in the data flow
