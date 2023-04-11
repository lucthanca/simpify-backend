<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Api;

interface AppRepositoryInterface
{
    /**
     * Get app by related app layout id
     *
     * @param int $lId
     * @return Data\AppInterface
     */
    public function getByLayoutId(int $lId): Data\AppInterface;
}
