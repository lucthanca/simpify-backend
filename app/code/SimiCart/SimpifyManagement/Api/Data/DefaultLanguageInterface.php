<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Api\Data;

interface DefaultLanguageInterface
{
    const TEXT = 'text';

    /**
     * Get text
     *
     * @return string
     */
    public function getText(): ?string;

    /**
     * Set default text
     *
     * @param mixed $text
     * @return $this
     */
    public function setText($text): self;
}
