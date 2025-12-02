# Filament Permissions Package

This package auto-generates CRUD permissions for all Filament resources using Spatie permissions.

## Installation

1. Add path repository:

"""json
"repositories": [
    {
        "type": "path",
        "url": "packages/whitedev/filament-permissions"
    }
]
"""

2. Install package:

```bash
composer require whitedev/filament-permissions:@dev
```

3. Publish config:

```bash
php artisan vendor:publish --tag=config
```

4. Generate permissions:

```bash
php artisan filament-permissions:generate
```