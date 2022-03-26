<?php

namespace Taskforce\Actions;

abstract class DefaultAction {

    private $idUser;

    public function __construct($idExecutor, $idCustomer = null) {

        $this->idExecutor = $idExecutor;
        $this->idCustomer = $idCustomer;

    }

    abstract public function getTitle();

    abstract public function getInternalName();

    abstract public function checkRights(int $idUser):bool;

};

?>
