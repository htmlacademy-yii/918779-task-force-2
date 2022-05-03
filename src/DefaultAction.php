<?php

namespace Taskforce;

abstract class DefaultAction {

    private $idUser;

    public function __construct(int $idExecutor, int $idCustomer = null) {

        $this->idExecutor = $idExecutor;
        $this->idCustomer = $idCustomer;

    }

    abstract public function getTitle();

    abstract public function getInternalName();

    abstract public function checkRights(int $idUser): bool;

};

?>
