<?php

namespace Taskforce\Import;

class CityImport extends BasicImport {

    public function getColumnNames():string {
        return 'name, lat, lng';
     }

}
