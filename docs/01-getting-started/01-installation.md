# Installation

How to install and register the Nadi CakePHP SDK in your application.

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.1+ |
| CakePHP | 4.5+ or 5.x |

## Install via Composer

```bash
composer require nadi-pro/nadi-cakephp
```

## Register the Plugin

Load the Nadi plugin in your `Application.php` bootstrap method:

```php
// src/Application.php

public function bootstrap(): void
{
    parent::bootstrap();
    $this->addPlugin(\Nadi\CakePHP\NadiPlugin::class);
}
```

The plugin automatically:

- Loads the `config/nadi.php` configuration if not already loaded
- Creates the transporter instance
- Registers the exception handler
- Adds HTTP monitoring middleware

## Load Configuration

The plugin loads its configuration automatically. To customize settings, copy the default config:

```bash
cp vendor/nadi-pro/nadi-cakephp/config/nadi.php config/nadi.php
```

Or load it explicitly in your `config/app.php`:

```php
Configure::load('nadi');
```

## Next Steps

- [Quick Start](02-quick-start.md) - Get monitoring running
- [Configuration Reference](../03-configuration/01-drivers.md) - Configure transport drivers
