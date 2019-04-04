# DSN parser

## Install

Via Composer

``` bash
composer require mikeweb85/dsn
```


## Usage

The DSN parser is super simple to use. See the following example: 

```php
$dsn = new DSN('mysql://root:root_pass@127.0.0.1:3306/test_db/test_table');

$dsn->isValid();      // true
$dsn->getProtocol();  // 'mysql'
$dsn->getUsername();  // 'root'
$dsn->getPassword();  // 'root_pass'
$dsn->getFirstHost(); // '127.0.0.1'
$dsn->getFirstPort(); // 3306
$dsn->getDatabase();  // 'test_db'
$dsn->getTable();  // 'test_table'
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
