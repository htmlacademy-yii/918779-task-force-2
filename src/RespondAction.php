<?php

namespace Taskforce;

class RespondAction extends DefaultAction {

    public const ACTION_RESPOND = 'respond';

    public function getTitle() {

        return 'Откликнуться на задание';

    }

    public function getInternalName() {

        return self::ACTION_RESPOND;

    }

    public function checkRights() {

        return 'executor';

    }

};

?>
