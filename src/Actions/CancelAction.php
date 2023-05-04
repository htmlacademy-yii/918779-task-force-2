<?php

namespace Taskforce\Actions;
use Yii;

class CancelAction extends DefaultAction {

    public const ACTION_CANCEL = 'cancel';
    public const ACTION_CLASS = 'button button--pink action-btn';
    public const ACTION_DATA = 'completion';

    /**
     * Gets an InternalName
     *
     * @return string
     */
    public function getInternalName():string
    {
        return self::ACTION_CANCEL;
    }

    /**
     * Gets a Title
     *
     * @return string
     */
    public function getTitle():string
    {
        return 'Отменить задание';
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
        return $this->idCustomer === $idUser;
    }
};
?>
