<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

interface FeatureFieldOptionInterface
{
    const ID = 'entity_id';
    const IS_DEFAULT = 'is_default';
    const LABEL = 'label';
    const VALUE = 'value';
    const FIELD_ID = 'field_id';

    /**
     * Get is default selected option
     *
     * @return int
     */
    public function getIsDefault(): int;

    /**
     * Set is default selected option
     *
     * @param int $v
     * @return $this
     */
    public function setIsDefault(int $v): self;

    /**
     * Get option label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Set option label
     *
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self;

    /**
     * Get option value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Set option value
     *
     * @param string $v
     * @return $this
     */
    public function setValue(string $v): self;

    /**
     * Get related field id
     *
     * @return int
     */
    public function getFieldId(): int;

    /**
     * Set field Id
     *
     * @param int $id
     * @return $this
     */
    public function setFieldId(int $id): self;
}
