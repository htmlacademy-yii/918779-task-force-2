<?php

namespace Taskforce\Actions;

class DoneAction extends DefaultAction {

    public const ACTION_DONE = 'done';

    public function getTitle() {

        return 'Выполнено';

    }

    public function getInternalName() {

        return self::ACTION_DONE;

    }

    public function checkRights(int $idUser):bool { 
        
        return $this->idCustomer === $idUser;

    }
        
};

?>
