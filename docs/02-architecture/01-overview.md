# Architecture Overview

High-level architecture of the Nadi CakePHP SDK and how monitoring data flows through the system.

## Component Overview

| Component | Class | Purpose |
|-----------|-------|---------|
| Plugin | `NadiPlugin` | Entry point; bootstraps the SDK, registers middleware and commands |
| Facade | `Nadi` | Static singleton interface to the transporter |
| Transporter | `Transporter` | Configures driver and sampling; manages the send pipeline |
| Middleware | `NadiMiddleware` | Intercepts HTTP requests to measure duration and capture responses |
| OTel Middleware | `OpenTelemetryMiddleware` | Creates OpenTelemetry spans with trace context propagation |
| Handlers | `Handle*Event` | Process exceptions, HTTP events, and database queries into entries |
| Entries | `Entry`, `ExceptionEntry` | Data models that structure monitoring payloads |
| Metrics | `Application`, `Framework`, `Http`, `Network` | Collect environment and runtime metrics |

## Data Flow

```text
CakePHP Application
    |
    +-- Exception occurs
    |       |
    |       v
    |   HandleExceptionEvent --> ExceptionEntry --> Nadi::store()
    |
    +-- HTTP Request/Response
    |       |
    |       v
    |   NadiMiddleware --> HandleHttpRequestEvent --> Entry --> Nadi::store()
    |
    +-- Database Query
            |
            v
        HandleQueryEvent (if slow) --> Entry --> Nadi::store()
                                                     |
                                                     v
                                               Transporter
                                                     |
                                              SamplingManager
                                                     |
                                               Service::send()
                                                     |
                                          +----------+----------+
                                          |          |          |
                                         Log       HTTP    OpenTelemetry
```

## Bootstrap Sequence

When the plugin bootstraps, the following happens in order:

1. Configuration is loaded from `config/nadi.php` (if not already loaded)
2. If Nadi is disabled via config, bootstrap exits early
3. `Transporter::make()` creates a new transporter with the configured driver and sampling strategy
4. The transporter instance is set on the `Nadi` singleton facade
5. The exception handler is registered (wrapping any existing handler)
6. Middleware is added to the CakePHP middleware queue

## Request Lifecycle

For each HTTP request:

1. `NadiMiddleware` records the start time
2. The request passes through to the application
3. After the response is generated, `HandleHttpRequestEvent` processes the request/response pair
4. If the status code is not in the ignored list, an entry is created with metrics
5. The entry is stored via `Nadi::store()`
6. When the request ends, `Transporter::__destruct()` sends all queued data

## Next Steps

- [Patterns](02-patterns.md) - Design patterns used in the SDK
- [Exception Monitoring](../04-monitoring/01-exceptions.md) - How exceptions are captured
