<?php

namespace  Taskforce;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_WORKING = 'working';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'cancel';
    const ACTION_RESPOND = 'respond';
    const ACTION_DONE = 'done';
    const ACTION_REFUSED = 'refused';

    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';

    public const NEXT_STATUS = [
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_RESPOND => self::STATUS_WORKING,
        self::ACTION_DONE => self::STATUS_DONE,
        self::ACTION_REFUSED => self::STATUS_FAILED,
    ];

    public const ALLOWED_ACTIONS = [
        self::STATUS_NEW => [
            self::CUSTOMER => self::ACTION_CANCEL,
            self::EXECUTOR => self::ACTION_RESPOND,
        ],
        self::STATUS_WORKING => [
            self::CUSTOMER => self::ACTION_DONE,
            self::EXECUTOR => self::ACTION_REFUSED,
        ],
    ];

    private $idUser;

    public static $mapStatuses = [

        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_WORKING => 'В работе',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Провалено'

    ];

    public static $mapActions = [

        self::ACTION_CANCEL => 'Отменить задание',
        self::ACTION_RESPOND => 'Откликнуться на задание',
        self::ACTION_DONE => 'Задание выполнено',
        self::ACTION_REFUSED => 'Отказаться от задания'

    ];

    private function setIdCustomer ($idUser) {

        $this->idCustomer = $idUser;

    }

    private function setIdExecutor ($idUser) {

        $this->idExecutor = $idUser;

    }

    public function __construct($idExecutor, $idCustomer = null) {

        $this->setIdCustomer($idCustomer);
        $this->setIdExecutor($idExecutor);

    }

    public static function getMapStatuses() {

        return self::$mapStatuses;

    }

    public static function getMapActions() {

        return self::$mapActions;

    }

    public function getStatusByAction($action) {

        return self::NEXT_STATUS[$action] ?? '';

    }

    public function getAllowedAction($status, $role) {

        return self::ALLOWED_ACTIONS[$status][$role] ?? [];
    }

}

?>
