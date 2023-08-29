# Saucenao-PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nekoding/saucenao-php.svg?style=flat-square)](https://packagist.org/packages/nekoding/saucenao-php)
[![Total Downloads](https://img.shields.io/packagist/dt/nekoding/saucenao-php.svg?style=flat-square)](https://packagist.org/packages/nekoding/saucenao-php)
![GitHub Actions](https://github.com/nekoding/saucenao-php/actions/workflows/main.yml/badge.svg)

Unofficial PHP wrapper for SauceNAO's API heavily inspired by [saucenao_api](https://github.com/nomnoms12/saucenao_api) & [saucenao-nim](https://github.com/filvyb/saucenao-nim)

## Installation

You can install the package via composer:

```bash
composer require nekoding/saucenao-php
```

## Usage

```php
$saucenao = new \Nekoding\Saucenao\Saucenao('your api key here');
$result = $saucenao->fromUrl('https://redacted.com/your-image-here.jpg');

// or check folder example
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The GNU GPLv3. Please see [License File](LICENSE.md) for more information.
