<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Api\Data;

interface FeatureInterface
{
    const NAME = 'name';
    const STATUS = 'status';

    /**
     * Get feature name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set feature name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Get feature status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set feature status
     *
     * @param bool $value
     * @return $this
     */
    public function setStatus(int $value): self;
}
