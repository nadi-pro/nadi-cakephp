# Compatibility Matrix

Supported PHP and CakePHP version combinations for the Nadi CakePHP SDK.

## Version Support

| PHP Version | CakePHP 4.5 | CakePHP 5.x |
|-------------|-------------|-------------|
| 8.1 | Supported | Not supported |
| 8.2 | Supported | Supported |
| 8.3 | Supported | Supported |
| 8.4 | Not supported | Supported |

> **Note**: CakePHP 4.x depends on `laminas/laminas-diactoros` v2, which does not support
> PHP 8.4. Use CakePHP 5.x for PHP 8.4 environments.

## CI Test Matrix

The SDK is tested in CI against these combinations:

| PHP | CakePHP | Status |
|-----|---------|--------|
| 8.4 | ^5.0 | Tested |
| 8.3 | ^5.0 | Tested |
| 8.3 | ^4.5 | Tested |
| 8.2 | ^5.0 | Tested |
| 8.2 | ^4.5 | Tested |
| 8.1 | ^4.5 | Tested |

## Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `nadi-pro/nadi-php` | ^2.0 | Core Nadi PHP SDK |
| `cakephp/cakephp` | ^4.5 or ^5.0 | CakePHP framework |

### Dev Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `phpunit/phpunit` | ^10.0 or ^11.0 | Testing |
| `mockery/mockery` | ^1.5 | Test mocking |
| `laravel/pint` | ^1.15 | Code formatting |

## Next Steps

- [Installation](../01-getting-started/01-installation.md) - Install the SDK
- [OpenTelemetry](01-opentelemetry.md) - Distributed tracing
