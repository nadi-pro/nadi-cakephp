# Nadi CakePHP SDK

Nadi monitoring SDK for CakePHP applications. Captures exceptions, slow queries, and HTTP errors for the [Nadi](https://nadi.pro) monitoring platform.

## Requirements

- PHP 8.1+
- CakePHP 4.5+ or 5.x

## Installation

```bash
composer require nadi-pro/nadi-cakephp
```

## Configuration

Load the plugin in your `Application.php`:

```php
public function bootstrap(): void
{
    parent::bootstrap();
    $this->addPlugin(\Nadi\CakePHP\NadiPlugin::class);
}
```

Add the configuration to your `config/app.php` or load `config/nadi.php`:

```php
Configure::load('nadi');
```

Set environment variables:

```
NADI_ENABLED=true
NADI_DRIVER=log
NADI_API_KEY=your-api-key
NADI_APP_KEY=your-app-key
```

## Console Commands

```bash
cake nadi:install          # Install Nadi
cake nadi:test             # Test connectivity
cake nadi:verify           # Verify configuration
cake nadi:update-shipper   # Update shipper binary
```

## License

MIT
