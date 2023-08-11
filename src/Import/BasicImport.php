<?php

namespace Taskforce\Import;

use Taskforce\Exceptions\FileFormatException;
use Taskforce\Exceptions\SourceFileException;
use SplFileObject;
use RuntimeException;

abstract class BasicImport
{
    public $filename;
    public $columns = [];
    private $fileObject;
    private $result = [];
    private $error = null;

    /**
     * BasicImport constructor.
     * @param $filename - Путь к файлу csv
     */

    public function __construct(string $filename)
    {

        $this->filename = $filename;
    }

    public function import(): void
    {
        if (!file_exists($this->filename)) {
            throw new SourceFileException("Файл не существует");
        }

        try {
            $this->fileObject = new SplFileObject($this->filename);
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Не удалось открыть файл для чтения");
        }

        if ($this->fileObject->getExtension() !== 'csv') {
            throw new FileFormatException('Неправильный формат файла');
        }

        $this->columns[] = $this->getHeaderData();

        foreach ($this->getNextLine() as $line) {
            $this->result[] = $line;
        }
    }

    public function writeDb(string $dirname): void
    {

        $basename = $this->fileObject->getBasename(".csv");
        $templates = "";

        $rows = $this->result;

        foreach ($rows as $row) {
            $template = sprintf(
                "INSERT INTO %s (%s) VALUES (%s);\n",
                $basename,
                $this->getColumnNames(),
                $this->toSQLRow($row)
            );
            $templates .= $template;
        }

        $sqlfile = sprintf("%s/%s.sql", $dirname, $basename);

        if (!file_put_contents($sqlfile, $templates)) {
            throw new SourceFileException("Не удалось экспортировать данные в файл");
        }
    }

    public function getColumnNames(): string
    {
        $row = $this->columns[0];
        $row = implode(", ", $row);
        return $row;
    }

    public function toSQLRow(array $row): string
    {
        $row = array_map(function ($row) {
            return "'{$row}'";
        },
        $row);
        $row = implode(", ", $row);
        return $row;
    }

    private function getHeaderData(): ?array
    {
        $this->fileObject->rewind();
        $data = $this->fileObject->fgetcsv();
        return $data;
    }

    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }
}
