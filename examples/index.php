<?php
    require_once '../vendor/autoload.php';

    use Milantex\DAW\DataBase;
    use Milantex\TSO\TableStructureDescriptor;

    $daw = new DataBase('localhost', 'demo7', 'root', '');

    $tso = new TableStructureDescriptor($daw);
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
