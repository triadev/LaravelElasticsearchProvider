# LaravelElasticsearchProvider

[![Software license][ico-license]](LICENSE)

A service provider for laravel to managing data versions in elasticsearch.

## Main features
- Laravel (>= 5.4) support for elasticsearch (>= 5.2)
- Migration
- Versioning

## Installation

### Composer
> composer require triadev/laravel-elasticsearch-provider

### Application
Register the service provider in the config/app.php (Laravel) or in the bootstrap/app.php (Lumen).
```
'providers' => [
    \Triadev\Es\Provider\ScElasticsearchServiceProvider::class
]
```

Once installed you can now publish your config file and set your correct configuration for using the package.
```php
php artisan vendor:publish --provider="Triadev\Es\Provider\ScElasticsearchServiceProvider" --tag="config"
```

This will create a file ```config/sc-elasticsearch.php```.

## Configuration
| Key        | Value           | Description  |
|:-------------:|:-------------:|:-----:|
| ELASTICSEARCH_HOST | STRING | Host |
| ELASTICSEARCH_PORT | INTEGER | Default: 9200 |
| ELASTICSEARCH_SCHEME | STRING | https or http |
| ELASTICSEARCH_USER | STRING | Username |
| ELASTICSEARCH_PASS | STRING | Password |

## Reporting Issues
If you do find an issue, please feel free to report it with GitHub's bug tracker for this project.

Alternatively, fork the project and make a pull request. :)

## Other

### Project related links
- [Wiki](https://github.com/triadev/LaravelElasticsearchProvider/wiki)
- [Issue tracker](https://github.com/triadev/LaravelElasticsearchProvider/issues)

### Author
- [Christopher Lorke](mailto:christopher.lorke@gmx.de)

### License
The code for LaravelElasticsearchProvider is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

[ico-license]: https://img.shields.io/github/license/triadev/LaravelElasticsearchProvider.svg?style=flat-square