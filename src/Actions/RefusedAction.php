<?php

namespace Taskforce\Actions;

class RefusedAction extends DefaultAction {

    public const ACTION_REFUSED = 'refused';

    public function getTitle() {

        return 'Отказаться от задания';

    }

    public function getInternalName() {

        return self::ACTION_REFUSED;

    }

    public function checkRights(int $idUser):bool { 
        
        return $this->idExecutor === $idUser;

    }

};

?>
