<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\View;

use Magento\Framework\ObjectManagerInterface;

class SimpifyPageFactory extends \Magento\Framework\View\Result\PageFactory
{
    private ObjectManagerInterface $objectManager;

    /**
     * Simpify Page Factory Constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $instanceName = SimpifyPage::class
    ) {
        parent::__construct($objectManager, $instanceName);
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    /**
     * Create new instance simpify page
     *
     * @param bool $isView
     * @param array $arguments
     * @return \Magento\Framework\View\Result\Page
     */
    public function create($isView = false, array $arguments = [])
    {
        $arguments['template'] = 'SimiCart_SimpifyManagement::simpify_base.phtml';

        /** @var \Magento\Framework\View\Result\Page $page */
        $page = $this->objectManager->create($this->instanceName, $arguments);
        // TODO Temporary solution for compatibility with View object. Will be deleted in
        if (!$isView) {
            $page->addDefaultHandle();
        }
        return $page;
    }
}
