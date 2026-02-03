# Console Commands

CLI commands provided by the Nadi CakePHP plugin for setup and diagnostics.

## Available Commands

| Command | Description |
|---------|-------------|
| `cake nadi:install` | Install the Nadi shipper binary |
| `cake nadi:test` | Send a test event to verify connectivity |
| `cake nadi:verify` | Verify the current Nadi configuration |
| `cake nadi:update-shipper` | Update or reinstall the shipper binary |

## Install

Install the shipper binary for local transport:

```bash
cake nadi:install
```

This downloads and installs the Nadi shipper binary used by the log transport driver.

## Test

Send a test event to the configured transport:

```bash
cake nadi:test
```

Use this to verify that events are being sent and received by the Nadi platform.

## Verify

Validate that the Nadi configuration is complete and correct:

```bash
cake nadi:verify
```

Checks that required configuration keys are set and the transport driver is valid.

## Update Shipper

Update the shipper binary to the latest version:

```bash
cake nadi:update-shipper
```

This reinstalls the shipper binary, replacing the existing version.

## Next Steps

- [Drivers](../03-configuration/01-drivers.md) - Configure transport drivers
- [Architecture Overview](../02-architecture/01-overview.md) - Understand how the SDK works
