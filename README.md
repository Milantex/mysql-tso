# What is MySQL-TSO?
This package provides a mechanism that analyzes the structure of tables in a MySQL/MariaDB database. It might be better to see an example of how to use it instead of reading a lengthy description.

## Installation
#### Using composer in the command line
You can use composer to install the package using the following command from within your project's source directory:

`composer require milantex/mysql-tso`

Make sure to update your autoloader if needed:

`composer dump-autoload -o`

#### Requiring the package as a dependency in composer.json
Add the following code to your composer.json. Here is an example of a composer.json file with the milantex/mysql-tso package required:

```javascript
{
    "name": "your-name/your-project",
    "description": "Your project's description",
    "type": "project",
    "license": "MIT",
    "authors": [
        {
            "name": "Your Name",
            "email": "your@mail.tld"
        }
    ],
    "require": {
        "milantex/mysql-tso": "*"
    }
}
```

Make sure to run the composer command to install dependencies:

`composer install`

## Using the library in your project

```php
    require_once '../vendor/autoload.php';

    use Milantex\DAW\DataBase;
    use Milantex\TSO\TableStructureDescriptor;

    # Enter database parameters
    $daw = new DataBase('localhost', 'demo', 'root', '');

    # Instantiate a table structure descriptor object
    $tso = new TableStructureDescriptor($daw);

    # Start the database analysis
    $tso->analyse();

    if ($tso->tableExists('page') &&
        $tso->getTableStructure('page')->fieldExists('title')) {
        echo 'Can page.title be null? ';
        if ($tso->getTableStructure('page')
                ->getFieldStructure('title')
                ->isNullable()) {
            echo 'Yes';
        } else {
            echo 'No';
        }
    }
```
