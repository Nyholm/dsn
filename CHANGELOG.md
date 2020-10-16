# Change Log

The change log describes what is "Added", "Removed", "Changed" or "Fixed" between each release.

## 2.0.0

No changes since beta2.

## 2.0.0@beta2

### Added

* DsnParser::parseUrl(string $dsn): Url
* DsnParser::parsePath(string $dsn): Path

## 2.0.0@beta1

Version 2 comes with a new definition was a DSN really is. It supports functions
and a greater variety of DSN formats.

### Changed

The `Nyholm\Dsn` class has been replaced with `Nyholm\Dsn\DsnParser`. To get a `Dsn`
object:

```php

// Before
$dsn = new \Nyholm\DSN('mysql://localhost');

// After
$dsn = new \Nyholm\Dsn\DsnParser::parse('mysql://localhost');
```

## 1.0.0

No changes since 0.1.1.

## 0.1.1

Support for PHP 7.3.

## 0.1.0

Initial release.
