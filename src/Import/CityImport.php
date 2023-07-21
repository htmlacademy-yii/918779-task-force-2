<?php

namespace Taskforce\Import;

class CityImport extends BasicImport
{
    public function getColumnNames(): string
    {
        return 'title, lat, lng';
    }
}
