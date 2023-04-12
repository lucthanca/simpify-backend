<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

interface FeatureFieldInterface
{
    const NAME = 'name';
    const INPUT_TYPE = 'input_type';
    const FEATURE_ID = 'feature_id';

    /**
     * Get field config name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set feature config name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Get config input type
     *
     * @return string
     */
    public function getInputType(): string;

    /**
     * Set config input type
     *
     * @param string $type
     * @return $this
     */
    public function setInputType(string $type): self;

    /**
     * Get parent feature id
     *
     * @return int
     */
    public function getFeatureId(): int;

    /**
     * Associate to feature
     *
     * @param int $id
     * @return $this
     */
    public function setFeatureId(int $id): self;
}
