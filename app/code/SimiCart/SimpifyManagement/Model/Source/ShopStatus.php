<?php
declare(strict_types=1);
namespace SimiCart\SimpifyManagement\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ShopStatus implements OptionSourceInterface
{
    const NOT_COMPLETED = 9;
    const INSTALLED = 1;
    const UNINSTALLED = 0;

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::NOT_COMPLETED,
                'label' => "<span style='{$this->getNotCompletedStyle()}'>" .
                    __('Installation Not Completed') .
                    "</span>"
            ],
            [
                'value' => self::UNINSTALLED,
                'label' => "<span style='{$this->getUninstalledStyle()}'>". __('Uninstalled') . "</span>"
            ],
            [
                'value' => self::INSTALLED,
                'label' => "<span style='{$this->getInstalledStyle()}'>" . __('Installed') . "</span>"
            ],
        ];
    }

    /**
     * Get base style
     *
     * @return string
     */
    protected function getBaseStyle(): string
    {
        return 'font-weight: 600;display: inline-block;border-radius: 3px;padding: 4px 7px;';
    }

    /**
     * Get installation not completed status style
     *
     * @return string
     */
    public function getNotCompletedStyle(): string
    {
        return $this->getBaseStyle() . 'background: lightslategray;color: white';
    }

    /**
     * Get installed status style
     *
     * @return string
     */
    protected function getInstalledStyle(): string
    {
        return $this->getBaseStyle() . 'color: black; background: lawngreen;';
    }

    /**
     * Get uninstalled status style
     *
     * @return string
     */
    protected function getUninstalledStyle(): string
    {
        return 'background: orangered;color: white;' . $this->getBaseStyle();
    }
}
