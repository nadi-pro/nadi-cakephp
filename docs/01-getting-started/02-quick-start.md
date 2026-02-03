# Quick Start

Minimal steps to start monitoring your CakePHP application with Nadi.

## Step 1: Set Environment Variables

Add these to your `.env` or environment configuration:

```bash
NADI_ENABLED=true
NADI_DRIVER=http
NADI_API_KEY=your-api-key
NADI_APP_KEY=your-app-key
```

For local development, use the `log` driver to write monitoring data to disk:

```bash
NADI_ENABLED=true
NADI_DRIVER=log
```

## Step 2: Verify Configuration

Run the verify command to check your setup:

```bash
cake nadi:verify
```

## Step 3: Test Connectivity

Send a test event to confirm the connection:

```bash
cake nadi:test
```

## What Gets Monitored

Once configured, the SDK automatically captures:

| Feature | Description | Default Behavior |
|---------|-------------|------------------|
| Exceptions | Unhandled exceptions with stack traces and code context | Always captured |
| HTTP Errors | Non-success HTTP responses (4xx, 5xx) | Success status codes ignored |
| Slow Queries | Database queries exceeding threshold | Threshold: 500ms |

No additional code changes are required. The plugin hooks into CakePHP's middleware pipeline and exception handling automatically.

## Next Steps

- [Console Commands](03-console-commands.md) - Available CLI commands
- [Drivers](../03-configuration/01-drivers.md) - Configure transport drivers
- [Monitoring Features](../04-monitoring/README.md) - Detailed monitoring guides
