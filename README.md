# LaravelElasticsearchProvider

[![Software license][ico-license]](LICENSE)
[![Travis][ico-travis]][link-travis]
[![Coveralls](https://coveralls.io/repos/github/triadev/LaravelElasticsearchProvider/badge.svg?branch=master)](https://coveralls.io/github/triadev/LaravelElasticsearchProvider?branch=master)
[![CodeCov](https://codecov.io/gh/triadev/LaravelElasticsearchProvider/branch/master/graph/badge.svg)](https://codecov.io/gh/triadev/LaravelElasticsearchProvider)
[![Latest stable][ico-version-stable]][link-packagist]
[![Latest development][ico-version-dev]][link-packagist]
[![Monthly installs][ico-downloads-monthly]][link-downloads]

A service provider for laravel to managing data versions in elasticsearch.

## Main features
- Laravel (>= 5.4) support for elasticsearch (>= 5.2)
- Migration
- Versioning
- Deploy

## Installation

### Composer
> composer require triadev/laravel-elasticsearch-provider

### Application
Register the service provider in the config/app.php (Laravel) or in the bootstrap/app.php (Lumen).
```
'providers' => [
    \Triadev\Es\Provider\ElasticsearchServiceProvider::class
]
```

Once installed you can now publish your config file and set your correct configuration for using the package.
```php
php artisan vendor:publish --provider="Triadev\Es\Provider\ElasticsearchServiceProvider" --tag="config"
```

This will create a file ```config/triadev-elasticsearch.php```.

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

## Test

1. docker-compose up
2. docker exec fpm ./vendor/phpunit/phpunit/phpunit

## Other

### Project related links
- [Wiki](https://github.com/triadev/LaravelElasticsearchProvider/wiki)
- [Issue tracker](https://github.com/triadev/LaravelElasticsearchProvider/issues)

### Author
- [Christopher Lorke](mailto:christopher.lorke@gmx.de)

### License
The code for LaravelElasticsearchProvider is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

[ico-license]: https://img.shields.io/github/license/triadev/LaravelElasticsearchProvider.svg?style=flat-square
[ico-version-stable]: https://img.shields.io/packagist/v/triadev/laravel-elasticsearch-provider.svg?style=flat-square
[ico-version-dev]: https://img.shields.io/packagist/vpre/triadev/laravel-elasticsearch-provider.svg?style=flat-square
[ico-downloads-monthly]: https://img.shields.io/packagist/dm/triadev/laravel-elasticsearch-provider.svg?style=flat-square
[ico-travis]: https://travis-ci.org/triadev/LaravelElasticsearchProvider.svg?branch=master

[link-packagist]: https://packagist.org/packages/triadev/laravel-elasticsearch-provider
[link-downloads]: https://packagist.org/packages/triadev/laravel-elasticsearch-provider/stats
[link-travis]: https://travis-ci.org/triadev/LaravelElasticsearchProvider
