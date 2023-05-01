<?php

namespace Taskforce\Actions;

use Yii;
use Taskforce\Tasks;
use app\models\Response;

class RespondAction extends DefaultAction {

    public const ACTION_RESPOND = 'respond';
    public const ACTION_CLASS = 'button button--blue action-btn';
    public const ACTION_DATA = 'act_response';

    /**
     * Gets an InternalName
     *
     * @return string
     */
    public function getInternalName():string
    {
        return self::ACTION_RESPOND;
    }

    public function getTitle():string
    {
        return 'Откликнуться на задание';
    }

    /**
     * Gets a Class
     *
     * @return string
     */
    public function getClass(): string
    {
        return self::ACTION_CLASS;
    }

    /**
     * Gets a Data
     *
     * @return string
     */
    public function getData():string
    {
        return self::ACTION_DATA;
    }

    /**
     * Checks rights
     *
     * @return bool
     */
    public function checkRights(int $idUser): bool
    {
        return $this->idExecutor !== $idUser || Yii::$app->user->identity->role === Tasks::EXECUTOR;
    }

};

?>
