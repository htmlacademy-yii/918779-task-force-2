<?php

namespace Taskforce\Actions;

class DoneAction extends DefaultAction {

    public const ACTION_DONE = 'done';
    public const ACTION_CLASS = 'button button--pink action-btn';
    public const ACTION_DATA = 'completion';

    /**
     * Gets an InternalName
     *
     * @return string
     */
    public function getInternalName():string
    {
        return self::ACTION_DONE;
    }

    /**
     * Gets a Title
     *
     * @return string
     */
    public function getTitle():string
    {
        return 'Выполнено';
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
     * @return int
     */
    public function checkRights(int $idUser): bool
    {
        return $this->idCustomer === $idUser;
    }

};

?>
