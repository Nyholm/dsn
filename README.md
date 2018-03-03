# HTTPlug Bundle

[![Latest Version](https://img.shields.io/github/release/Nyholm/dsn.svg?style=flat-square)](https://github.com/Nyholm/dsn/releases)
[![Build Status](https://img.shields.io/travis/Nyholm/dsn.svg?style=flat-square)](https://travis-ci.org/Nyholm/dsn)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Nyholm/dsn.svg?style=flat-square)](https://scrutinizer-ci.com/g/Nyholm/dsn)
[![Quality Score](https://img.shields.io/scrutinizer/g/Nyholm/dsn.svg?style=flat-square)](https://scrutinizer-ci.com/g/Nyholm/dsn)
[![Total Downloads](https://img.shields.io/packagist/dt/nyholm/dsn.svg?style=flat-square)](https://packagist.org/packages/nyholm/dsn)


## Install

Via Composer

``` bash
composer require nyholm/dsn
```


## Usage

The DSN parser is super simple to use. See the following example: 

```php
$dsn = new DSN('mysql://root:root_pass@127.0.0.1:3306/test_db');

$dsn->isValid();      // true
$dsn->getProtocol();  // 'mysql'
$dsn->getUsername();  // 'root'
$dsn->getPassword();  // 'root_pass'
$dsn->getFirstHost(); // '127.0.0.1'
$dsn->getFirstPort(); // 3306
$dsn->getDatabase();  // 'test_db'
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credit

This project is inpspired by [SncRedisBundle](https://github.com/snc/SncRedisBundle/blob/master/DependencyInjection/Configuration/RedisDsn.php)
and [PHP-cache](https://github.com/php-cache/adapter-bundle/blob/master/src/DSN.php).
