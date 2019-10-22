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
composer require spatie/db-dumper
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

### Do not write CREATE TABLE statements that create each dumped table.
```php
$dumpCommand = MySql::create()
    ->setDbName('dbname')
    ->setUserName('username')
    ->setPassword('password')
    ->doNotCreateTables()
    ->getDumpCommand('dump.sql', 'credentials.txt');
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

With MySql, you also have the option to use the `--all-databases` extra option. This is useful when you want to run a full backup of all the databases in the specified MySQL connection.

```php
$dumpCommand = MySql::create()
    ->setUserName('username')
    ->setPassword('password')
    ->addExtraOption('--all-databases')
    ->getDumpCommand('dump.sql', 'credentials.txt');
```

Please note that using the `->addExtraOption('--databases dbname')` or `->addExtraOption('--all-databases')` will override the database name set on a previous `->setDbName()` call.

### Using compression
If you want to compress the outputted file, you can use one of the compressors and the resulted dump file will be compressed.

There is one compressor that comes out of the box: `GzipCompressor`. It will compress your db dump with `gzip`. Make sure `gzip` is installed on your system before using this.

```php
$dumpCommand = MySql::create()
    ->setDbName('dbname')
    ->setUserName('username')
    ->setPassword('password')
    ->useCompressor(new GzipCompressor())
    ->dumpToFile('dump.sql.gz');
```

### Creating your own compressor

You can create you own compressor implementing the `Compressor` interface. Here's how that interface looks like:

```php
namespace pierresilva\DbDumper\Compressors;

interface Compressor
{
    public function useCommand(): string;

    public function useExtension(): string;
}
```

The `useCommand` should simply return the compression command the db dump will get pumped to. Here's the implementation of `GzipCompression`.

```php
namespace pierresilva\DbDumper\Compressors;

class GzipCompressor implements Compressor
{
    public function useCommand(): string
    {
        return 'gzip';
    }

    public function useExtension(): string
    {
        return 'gz';
    }
}
```

## Testing

``` bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
