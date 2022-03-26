<?php

namespace Taskforce\Actions;

class RespondAction extends DefaultAction {

    public const ACTION_RESPOND = 'respond';

    public function getTitle() {

        return 'Откликнуться на задание';

    }

    public function getInternalName() {

        return self::ACTION_RESPOND;

    }

    public function checkRights(int $idUser):bool { 
        
        return $this->idExecutor === $idUser;

    }

};

?>
