<?php

namespace Taskforce\Actions;

abstract class DefaultAction
{
    private $idUser;

    public function __construct(int $idCustomer, int $idExecutor = null)
    {
        $this->idCustomer = $idCustomer;
        $this->idExecutor = $idExecutor;
    }

    /**
     * Gets an InternalName
     *
     * @return string
     */
    abstract public function getInternalName(): string;

    /**
     * Gets a Title
     *
     * @return string
     */
    abstract public function getTitle(): string;

    /**
     * Gets a Class
     *
     * @return string
     */
    abstract public function getClass(): string;

    /**
     * Gets a Data
     *
     * @return string
     */
    abstract public function getData(): string;

    /**
     * Checks rights
     *
     * @return bool
     */
    abstract public function checkRights(int $idUser): bool;
}
