<?php

namespace Taskforce\Actions;

use Yii;
use Taskforce\Tasks;

class RefusedAction extends DefaultAction
{
    public const ACTION_REFUSED = 'refused';
    public const ACTION_CLASS = 'button button--orange action-btn';
    public const ACTION_DATA = 'refusal';

    /**
     * Gets an InternalName
     *
     * @return string
     */
    public function getInternalName(): string
    {
        return self::ACTION_REFUSED;
    }

    /**
     * Gets a Title
     *
     * @return string
     */

    public function getTitle(): string
    {
        return 'Отказаться от задания';
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
    public function getData(): string
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
        return $this->idExecutor === $idUser;
    }
};
