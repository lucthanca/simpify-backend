<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Formatter;

use Magento\Framework\Exception\NoSuchEntityException;
use SimiCart\SimpifyManagement\Api\Data\ThemeInterface;

trait ThemeFormatterTrait
{
    /**
     * Format data item output
     *
     * @param ThemeInterface $i
     * @return array
     * @throws NoSuchEntityException
     */
    protected function formatOutput(ThemeInterface $i)
    {
        return [
            'uid' => base64_encode($i->getId()),
            'name' => $i->getName(),
            'is_active' => (bool) $i->getStatus(),
            'image' => $i->getImageUrl(),
            'preview_images' => $i->getPreviewImagesAsArray(),
            'colors' => $i->getPairedColors(),
            'model' => $i,
        ];
    }
}
