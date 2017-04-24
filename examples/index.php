<?php
    require_once '../vendor/autoload.php';

    use Milantex\DAW\DataBase;
    use Milantex\TSO\TableStructureDescriptor;

    $daw = new DataBase('localhost', 'demo7', 'root', '');

    $tso = new TableStructureDescriptor($daw);
    $tso->analyse();

    $pageTable = $tso->getTableStructure('page');
    if ($pageTable) {
        $titleField = $pageTable->getFieldStructure('title');
        if ($titleField) {
            echo 'Can page.title be null? ' . ($titleField->isNullable()?'Yes':'No');
        }
    }
