<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Controller\Adminhtml\Theme;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Edit extends NewAction implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'SimiCart_SimpifyManagement::theme_update';
}
