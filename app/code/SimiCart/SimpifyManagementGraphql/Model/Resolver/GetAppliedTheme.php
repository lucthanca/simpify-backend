<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use SimiCart\SimpifyManagement\Api\Data\AppLayoutInterface;
use SimiCart\SimpifyManagement\Model\ThemeFactory;
use SimiCart\SimpifyManagementGraphql\Model\Formatter\ThemeFormatterTrait;


class GetAppliedTheme implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    use ThemeFormatterTrait;

    private ThemeFactory $themeFactory;

    /**
     * GetAppliedTheme constructor.
     *
     * @param ThemeFactory $themeFactory
     */
    public function __construct(ThemeFactory $themeFactory) {
        $this->themeFactory = $themeFactory;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!empty($value['model'])) {
            throw new GraphQlInputException(__("App Layout not found!"));
        }

        /** @var AppLayoutInterface $layout */
        $layout = $value['model'];
        $theme = $this->themeFactory->create()->load($layout->getThemeId());
        if (!$theme->getId()) {
            return null;
        }

        return $this->formatOutput($theme);
    }
}
