<?php

namespace Taskforce;

class DoneAction extends DefaultAction {

    public const ACTION_DONE = 'done';

    public function getTitle() {

        return 'Выполнено';

    }

    public function getInternalName() {

        return self::ACTION_DONE;

    }

    public function checkRights(int $idUser, int $idCustomer, int $idExecutor):bool {

        return $this->idCustomer === $idUser;

    }

};

?>
