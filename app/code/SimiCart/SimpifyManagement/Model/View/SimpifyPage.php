<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Model\View;

use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;

class SimpifyPage extends \Magento\Framework\View\Result\Page
{
    protected $template = 'SimiCart_SimpifyManagement::simpify_base.phtml';

    /**
     * @inheritDoc
     */
    public function addDefaultHandle()
    {
        $this->addHandle($this->getDefaultLayoutHandle());
        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function render(HttpResponseInterface $response)
    {
        $this->pageConfig->publicBuild();
        if ($this->getPageLayout()) {
            $config = $this->getConfig();
            $this->addDefaultBodyClasses();
            $addBlock = $this->getLayout()->getBlock('simpify.head.additional');
            $this->assign([
                'headAdditional' => $addBlock ? $addBlock->toHtml() : null,
                'htmlAttributes' => $this->pageConfigRenderer->renderElementAttributes($config::ELEMENT_TYPE_HTML)
            ]);

            $output = $this->getLayout()->getOutput();
            $this->assign('layoutContent', $output);
            $output = $this->renderPage();
            $this->translateInline->processResponseBody($output);
            $response->appendBody($output);
        } else {
            parent::render($response);
        }
        return $this;
    }
}
