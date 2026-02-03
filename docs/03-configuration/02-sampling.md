# Sampling Strategies

Sampling controls how much monitoring data is sent to the transport.
Use sampling to reduce data volume in high-traffic applications.

## Available Strategies

| Strategy | Class | Description |
|----------|-------|-------------|
| `fixed_rate` | `FixedRateSampling` | Sample a fixed percentage of events (default) |
| `dynamic_rate` | `DynamicRateSampling` | Adjust sampling rate based on load |
| `interval` | `IntervalSampling` | Sample events at fixed time intervals |
| `peak_load` | `PeakLoadSampling` | Reduce sampling during high load |

## Configuration

Set the strategy and its parameters via environment variables or `config/nadi.php`:

```bash
NADI_SAMPLING_STRATEGY=fixed_rate
NADI_SAMPLING_RATE=0.1
```

### Configuration Parameters

| Parameter | Environment Variable | Default | Description |
|-----------|---------------------|---------|-------------|
| Strategy | `NADI_SAMPLING_STRATEGY` | `fixed_rate` | Which sampling strategy to use |
| Sampling rate | `NADI_SAMPLING_RATE` | `0.1` | Percentage of events to sample (0.0 - 1.0) |
| Base rate | `NADI_SAMPLING_BASE_RATE` | `0.05` | Base sampling rate for dynamic strategies |
| Load factor | `NADI_SAMPLING_LOAD_FACTOR` | `1.0` | Multiplier for load-based adjustments |
| Interval seconds | `NADI_SAMPLING_INTERVAL_SECONDS` | `60` | Interval between samples for interval strategy |

### Full Configuration Example

```php
// config/nadi.php
'sampling' => [
    'strategy' => env('NADI_SAMPLING_STRATEGY', 'fixed_rate'),
    'config' => [
        'sampling_rate' => env('NADI_SAMPLING_RATE', 0.1),
        'base_rate' => env('NADI_SAMPLING_BASE_RATE', 0.05),
        'load_factor' => env('NADI_SAMPLING_LOAD_FACTOR', 1.0),
        'interval_seconds' => env('NADI_SAMPLING_INTERVAL_SECONDS', 60),
    ],
    'strategies' => [
        'dynamic_rate' => \Nadi\Sampling\DynamicRateSampling::class,
        'fixed_rate' => \Nadi\Sampling\FixedRateSampling::class,
        'interval' => \Nadi\Sampling\IntervalSampling::class,
        'peak_load' => \Nadi\Sampling\PeakLoadSampling::class,
    ],
],
```

## Strategy Details

### Fixed Rate

Samples a fixed percentage of all events. A rate of `0.1` means 10% of events are sent.

```bash
NADI_SAMPLING_STRATEGY=fixed_rate
NADI_SAMPLING_RATE=0.1
```

### Dynamic Rate

Adjusts the sampling rate based on current application load. Starts at the base rate and scales with the load factor.

```bash
NADI_SAMPLING_STRATEGY=dynamic_rate
NADI_SAMPLING_BASE_RATE=0.05
NADI_SAMPLING_LOAD_FACTOR=1.0
```

### Interval

Samples events at fixed time intervals rather than by percentage. Sends one sample every N seconds.

```bash
NADI_SAMPLING_STRATEGY=interval
NADI_SAMPLING_INTERVAL_SECONDS=60
```

### Peak Load

Reduces sampling during high-traffic periods to minimize overhead. The sampling rate decreases as load increases.

```bash
NADI_SAMPLING_STRATEGY=peak_load
NADI_SAMPLING_BASE_RATE=0.05
NADI_SAMPLING_LOAD_FACTOR=1.0
```

## Custom Strategies

Sampling strategies must implement the `\Nadi\Sampling\Contract` interface.
Register custom strategies in the `strategies` array:

```php
'strategies' => [
    'custom' => \App\Sampling\CustomSampling::class,
],
```

## Next Steps

- [Environment Variables](03-environment-variables.md) - Complete variable reference
- [Architecture Overview](../02-architecture/01-overview.md) - How sampling fits in the pipeline
