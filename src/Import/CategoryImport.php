<?php

namespace Taskforce\Import;

class CategoryImport extends BasicImport {

    public function getColumnNames():string {
        return 'title, icon';
     }

}
