<?php

namespace Taskforce;

class CancelAction extends DefaultAction {

    public const ACTION_CANCEL = 'cancel';

    public function getTitle() {

        return 'Отменить задание';

    }

    public function getInternalName() {

        return self::ACTION_CANCEL;

    }

    public function checkRights() {

        return 'customer';

    }

};

?>
