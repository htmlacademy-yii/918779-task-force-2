<?php

use Taskforce\Exceptions\FileFormatException;
use Taskforce\Exceptions\SourceFileException;
use Taskforce\Import\FileImport;
use Taskforce\Import\CityImport;
use Taskforce\Import\CategoryImport;

require_once "vendor/autoload.php";

try {
    $dataImporter = new CategoryImport('./data/category.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new CityImport('./data/city.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new FileImport('./data/user.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new FileImport('./data/task.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new FileImport('./data/attachment.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new FileImport('./data/response.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new FileImport('./data/review.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};

try {
    $dataImporter = new FileImport('./data/specialization.csv');
    $dataImporter->import();
    $dataImporter->writeDb('./data');
}

catch (SourceFileException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
}

catch (FileFormatException $e) {
    echo("Не удалось обработать csv файл: " .$e->getMessage());
};
