<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Api;

interface AppLayoutRepositoryInterface
{
    /**
     * Get layout for app
     *
     * @param int $id
     * @return Data\AppLayoutInterface
     */
    public function getByAppId(int $id): Data\AppLayoutInterface;

    /**
     * Load by id
     *
     * @param int $id
     * @return Data\AppLayoutInterface
     */
    public function getById(int $id): Data\AppLayoutInterface;
}
