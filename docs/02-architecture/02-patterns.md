# Design Patterns

Patterns and conventions used throughout the Nadi CakePHP SDK.

## Singleton Facade

The `Nadi` class provides a static interface to the `Transporter` instance.
This allows any part of the SDK to store monitoring data without dependency injection:

```php
// Set during bootstrap
Nadi::setInstance(Transporter::make());

// Used by handlers
Nadi::store($entry->toArray());

// Sends queued data
Nadi::send();
```

## Handler Pattern

Each monitoring concern has a dedicated handler class extending `Base`:

| Handler | Captures | Trigger |
|---------|----------|---------|
| `HandleExceptionEvent` | Unhandled exceptions | Exception handler (`set_exception_handler`) |
| `HandleHttpRequestEvent` | HTTP request/response pairs | `NadiMiddleware` |
| `HandleQueryEvent` | Slow database queries | Database event listener |

The `Base` handler provides two shared methods:

- `store(array $data)` - Delegates to `Nadi::store()`
- `hash(string $value)` - Generates SHA1 hash for deduplication

## Entry Data Models

Entries wrap monitoring data with consistent structure:

- `Entry` - General-purpose entry for HTTP and query events, attaches user identity and metrics
- `ExceptionEntry` - Extends the core Nadi exception entry with CakePHP-specific data

Both entry types use a fluent interface:

```php
Entry::make(Type::HTTP, $data)
    ->setHashFamily($hash)
    ->tags($tags)
    ->toArray();
```

## Trait-Based Concerns

Reusable behaviors are extracted into traits:

| Trait | Purpose | Used By |
|-------|---------|---------|
| `InteractsWithMetric` | Registers Application, Framework, Http, Network metrics | `Entry`, `ExceptionEntry` |
| `FetchesStackTrace` | Parses stack traces and identifies the caller outside vendor directories | `HandleQueryEvent` |

## Graceful Degradation

All monitoring operations are wrapped in try/catch blocks that silently catch errors.
This ensures monitoring failures never impact the application:

```php
try {
    $handler = new HandleExceptionEvent;
    $handler->handle($exception);
} catch (\Throwable $e) {
    // Silently ignore monitoring errors
}
```

This pattern is applied in:

- Exception handler registration (`NadiPlugin`)
- HTTP middleware (`NadiMiddleware`)
- OpenTelemetry middleware (`OpenTelemetryMiddleware`)

## Sensitive Data Masking

Headers and request parameters are filtered before storage. Configured values are replaced with `********`:

- **Hidden headers**: `authorization`, `php-auth-pw`
- **Hidden parameters**: `password`, `password_confirmation`

These lists are configurable via `Nadi.http.hidden_request_headers` and `Nadi.http.hidden_parameters`.

## Next Steps

- [Configuration Reference](../03-configuration/README.md) - All configuration options
- [Monitoring Features](../04-monitoring/README.md) - How each monitoring feature works
