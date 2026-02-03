# Slow Query Monitoring

The SDK detects and records database queries that exceed a configurable time threshold.

## How It Works

`HandleQueryEvent` receives the SQL query, execution time, and connection name.
If the execution time exceeds the slow query threshold, the query is recorded:

1. The query execution time is compared against the threshold (default: 500ms)
2. If below the threshold, the query is ignored
3. The caller is identified from the stack trace (first frame outside vendor directories)
4. OpenTelemetry database attributes are attached
5. The entry is stored with connection name, SQL, timing, and caller location

## Configuration

Set the slow query threshold in milliseconds:

```bash
NADI_QUERY_SLOW_THRESHOLD=500
```

Or in `config/nadi.php`:

```php
'query' => [
    'slow_threshold' => env('NADI_QUERY_SLOW_THRESHOLD', 500),
],
```

## Captured Data

| Field | Description |
|-------|-------------|
| `connection` | Database connection name (e.g., `default`) |
| `sql` | The SQL query that was executed |
| `time` | Execution time in milliseconds (formatted to 2 decimal places) |
| `slow` | Always `true` (only slow queries are captured) |
| `file` | Source file that initiated the query |
| `line` | Line number in the source file |

## Caller Detection

The SDK walks the stack trace to find the first frame outside vendor directories.
This identifies the application code that initiated the slow query,
rather than the ORM internals.

## Deduplication

Slow queries are deduplicated by hashing the SQL statement combined with the current date
(YYYY-MM-DD). The same query running multiple times on the same day produces one unique hash.

## Tags

Each slow query entry is tagged with:

- `slow` - Indicates this is a slow query
- `db.connection_name:{name}` - The database connection
- `db.operation:{type}` - The SQL operation (SELECT, INSERT, UPDATE, DELETE, etc.)
- `query.slow:true` - OTel-compatible slow query tag

## Next Steps

- [Exception Monitoring](01-exceptions.md) - Exception tracking
- [HTTP Request Monitoring](02-http-requests.md) - HTTP error tracking
- [Configuration](../03-configuration/03-environment-variables.md) - Query threshold configuration
