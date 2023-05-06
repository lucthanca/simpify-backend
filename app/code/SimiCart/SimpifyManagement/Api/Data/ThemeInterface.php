<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

interface ThemeInterface
{
    const ID = 'entity_id';
    const NAME = 'name';
    const IMAGE = 'image';
    const PREVIEW_IMAGES = 'preview_images';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const STATUS = 'status';
    const COLORS = 'colors';

    /**
     * Get theme colors
     *
     * @return string|null
     */
    public function getColors(): ?string;

    /**
     * Set theme colors
     *
     * @param string|null $colors
     * @return $this
     */
    public function setColors(?string $colors): self;

    // define all getter/setter methods for the fields above

    /**
     * Get theme status
     *
     * @return string
     */
    public function getStatus(): int;

    /**
     * Set theme status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self;

    /**
     * Get theme name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set theme name
     *
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self;

    /**
     * Get theme image
     *
     * @return string
     */
    public function getImage(): string;

    /**
     * Set theme image
     *
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self;

    /**
     * Get theme preview images
     *
     * @return string
     */
    public function getPreviewImages(): string;

    /**
     * Set theme preview images
     *
     * @param string|null $previewImages
     * @return $this
     */
    public function setPreviewImages(?string $previewImages): self;

    /**
     * Get theme created at
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Set theme created at
     *
     * @param string|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?string $createdAt): self;

    /**
     * Get theme updated at
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set theme updated at
     *
     * @param string|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?string $updatedAt): self;
}
