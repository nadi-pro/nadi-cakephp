# Exception Monitoring

The SDK automatically captures unhandled exceptions with full context, including stack traces,
code previews, and OpenTelemetry attributes.

## How It Works

During bootstrap, `NadiPlugin` registers a custom exception handler using `set_exception_handler()`.
This handler wraps any previously registered handler, ensuring it is still called
after Nadi processes the exception.

When an exception occurs:

1. The exception class, file, line, and message are extracted
2. A stack trace is captured (file and line for each frame)
3. Code context is extracted (20 lines around the exception line)
4. OpenTelemetry attributes are attached (exception, user, session, HTTP)
5. Tags are generated for categorization
6. A hash family is computed for deduplication (based on class, file, line, message, and date)
7. The entry is stored via `Nadi::store()`

## Captured Data

| Field | Description |
|-------|-------------|
| `class` | Exception class name (e.g., `RuntimeException`) |
| `file` | File where the exception was thrown |
| `line` | Line number of the exception |
| `message` | Exception message |
| `trace` | Stack trace with file and line for each frame |
| `line_preview` | Source code context (20 lines before and after) |
| `otel` | OpenTelemetry semantic attributes |

## Deduplication

Exceptions are deduplicated by hashing the combination of:

- Exception class name
- File path
- Line number
- Message
- Current date (YYYY-MM-DD)

The same exception occurring multiple times on the same day produces one unique hash,
preventing duplicate entries.

## Tags

Each exception entry is tagged with:

- Tags extracted from the exception object (if it implements a `tags()` method)
- `exception.type:{class}` - The exception class name
- `error.type:{class}` - The error type using OTel semantic conventions

## Graceful Failure

If Nadi fails to process the exception, the error is silently caught. The original
exception handler (if any) is always called, regardless of whether Nadi succeeds.

## Next Steps

- [HTTP Request Monitoring](02-http-requests.md) - Track HTTP errors
- [Architecture Overview](../02-architecture/01-overview.md) - How exception handling fits in
