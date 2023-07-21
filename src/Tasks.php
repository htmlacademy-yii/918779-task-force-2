<?php

namespace  Taskforce;

use Taskforce\Exceptions\NoAvailableActionsException;
use Taskforce\Actions\CancelAction;
use Taskforce\Actions\RespondAction;
use Taskforce\Actions\DoneAction;
use Taskforce\Actions\RefusedAction;
use app\models\Task;
use yii\db\ActiveQuery;
use yii;

class Tasks
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_WORKING = 'working';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    const FILTER_NEW = 'new';
    const FILTER_WORKING = 'working';
    const FILTER_CLOSED = 'closed';
    const FILTER_OVERDUE = 'overdue';

    public const CUSTOMER = 'customer';
    public const EXECUTOR = 'executor';

    const USER_STATUS_FREE = 'free';
    const USER_STATUS_BUSY = 'busy';

    private $status;

    /**
     * Task status depending on the completed action
     */
    public const NEXT_STATUS = [
        CancelAction::ACTION_CANCEL => self::STATUS_CANCELED,
        RespondAction::ACTION_RESPOND => self::STATUS_WORKING,
        DoneAction::ACTION_DONE => self::STATUS_DONE,
        RefusedAction::ACTION_REFUSED => self::STATUS_FAILED,
    ];

    /**
     * Actions depending on the task status and user role
     */
    public const ALLOWED_ACTIONS = [
        self::STATUS_NEW => [
            self::CUSTOMER => CancelAction::ACTION_CANCEL,
            self::EXECUTOR => RespondAction::ACTION_RESPOND,
        ],
        self::STATUS_WORKING => [
            self::CUSTOMER => DoneAction::ACTION_DONE,
            self::EXECUTOR => RefusedAction::ACTION_REFUSED,
        ],
    ];

    private function setIdCustomer ($idUser) {
        $this->idCustomer = $idUser;
    }

    private function setIdExecutor ($idUser) {
        $this->idExecutor = $idUser;
    }

    public function __construct(string $status, int $idCustomer, int $idExecutor = null) {
        $this->setIdCustomer($idCustomer);
        $this->setIdExecutor($idExecutor);
        $this->status = $status;
    }

    /**
     * Task status map
     */
    public static $mapStatuses = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_WORKING => 'В работе',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Провалено'
    ];

    /**
     * User action map
     */
    public static $mapActions = [
        CancelAction::ACTION_CANCEL => 'Отменить задание',
        RespondAction::ACTION_RESPOND => 'Откликнуться на задание',
        DoneAction::ACTION_DONE => 'Задание выполнено',
        RefusedAction::ACTION_REFUSED => 'Отказаться от задания',
    ];

    /**
     * Gets a Status Map
     *
     * @return array
     */
    public static function getMapStatuses(): array 
    {
        return self::$mapStatuses;
    }

    /**
     * Gets an Action Map
     *
     * @return array
     */
    public static function getMapActions(): array 
    {
        return self::$mapActions;
    }

    /**
     * Gets the status of the task depending on the Action.
     *
     * @return string
     */
    public function getStatusByAction(string $action): string {
        return self::NEXT_STATUS[$action] ?? '';
    }

    /**
     * Gets the status name of the task depending on the Status.
     *
     * @return string
     */
    public function getStatusName(string $status): string
    {
        return self::$mapStatuses[$status] ?? '';
    }

    /**
     * Gets the available Actions of the task depending on the Status of the Task and User role.
     *
     * @return object
     */
    public function getAvailableAction(int $idUser): ?object   {

        switch ($this->status) {
            case self::STATUS_NEW:
                switch (Yii::$app->user->identity->role) {
                    case self::CUSTOMER:
                        $availableAction = new CancelAction($this->idCustomer);
                        return $availableAction->checkRights($idUser) ? $availableAction : null;
                    case self::EXECUTOR:
                        $availableAction = new RespondAction($this->idCustomer);
                        return $availableAction->checkRights($idUser) ? $availableAction : null;
                }
            case self::STATUS_WORKING:
                switch (Yii::$app->user->identity->role) {
                    case self::CUSTOMER:
                        $availableAction = new DoneAction($this->idCustomer);
                        return $availableAction->checkRights($idUser) ? $availableAction : null;
                    case self::EXECUTOR:
                        $availableAction = new RefusedAction($this->idCustomer, $this->idExecutor);
                        return $availableAction->checkRights($idUser) ? $availableAction : null;
                }
            case self::STATUS_DONE:
                return null;

            case self::STATUS_FAILED:
                return null;
        }

        return $availableAction ?? null;
    }    
}
?>
