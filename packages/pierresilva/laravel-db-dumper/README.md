# Dump the contents of a database

This repo contains an easy to use class to dump a database using PHP. Currently MySQL, PostgreSQL, SQLite and MongoDB are supported. Behind
the scenes `mysqldump`, `pg_dump`, `sqlite3` and `mongodump` are used.

Here's are simple examples of how to create a database dump with different drivers:

**MySQL**
```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->dumpToFile('dump.sql');
```

**PostgreSQL**

```php
pierresilva\DbDumper\Databases\PostgreSql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->dumpToFile('dump.sql');
```

**SQLite**

```php
pierresilva\DbDumper\Databases\Sqlite::create()
    ->setDbName($pathToDatabaseFile)
    ->dumpToFile('dump.sql');
```

**MongoDB**

```php
pierresilva\DbDumper\Databases\MongoDb::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->enableCompression()
    ->dumpToFile('dump.gz');
```

## Requirements
For dumping MySQL-db's `mysqldump` should be installed.

For dumping PostgreSQL-db's `pg_dump` should be installed.

For dumping SQLite-db's `sqlite3` should be installed.

For dumping MongoDB-db's `mongodump` should be installed.

## Installation

You can install the package via composer:
``` bash
$ composer require pierresilva/laravel-db-dumper
```

## Usage

This is the simplest way to create a dump of a MySql db:

```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->dumpToFile('dump.sql');
```

If you're working with PostgreSQL just use that dumper, most methods are available on both the MySql. and PostgreSql-dumper.

```php
pierresilva\DbDumper\Databases\PostgreSql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->dumpToFile('dump.sql');
```

If the `mysqldump` (or `pg_dump`) binary is installed in a non default location you can let the package know by using the`setDumpBinaryPath()`-function:

```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDumpBinaryPath('/custom/location')
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->dumpToFile('dump.sql');
```

### Dump specific tables

Using an array:

```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->includeTables(['table1', 'table2', 'table3'])
    ->dumpToFile('dump.sql');
```
Using a string:

```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->includeTables('table1, table2, table3')
    ->dumpToFile('dump.sql');
```

### Excluding tables from the dump

Using an array:

```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->excludeTables(['table1', 'table2', 'table3'])
    ->dumpToFile('dump.sql');
```
Using a string:

```php
pierresilva\DbDumper\Databases\MySql::create()
    ->setDbName($databaseName)
    ->setUserName($userName)
    ->setPassword($password)
    ->excludeTables('table1, table2, table3')
    ->dumpToFile('dump.sql');
```



### Adding extra options
If you want to add an arbitrary option to the dump command you can use `addExtraOption`

```php
$dumpCommand = MySql::create()
    ->setDbName('dbname')
    ->setUserName('username')
    ->setPassword('password')
    ->addExtraOption('--xml')
    ->getDumpCommand('dump.sql', 'credentials.txt');
```

If you're working with MySql you can set the database name using `--databases` as an extra option. This is particularly useful when used in conjunction with the `--add-drop-database` `mysqldump` option (see the [mysqldump docs](https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html#option_mysqldump_add-drop-database)).

```php
$dumpCommand = MySql::create()
    ->setUserName('username')
    ->setPassword('password')
    ->addExtraOption('--databases dbname')
    ->addExtraOption('--add-drop-database')
    ->getDumpCommand('dump.sql', 'credentials.txt');
```

Please note that using the `->addExtraOption('--databases dbname')` will override the database name set on a previous `->setDbName()` call.


## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
