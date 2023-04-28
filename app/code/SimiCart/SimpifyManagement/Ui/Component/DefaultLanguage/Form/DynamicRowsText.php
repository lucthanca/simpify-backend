<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagement\Ui\Component\DefaultLanguage\Form;

use Magento\Ui\Component\Form\Fieldset;

class DynamicRowsText extends FieldSet
{
    public function prepare()
    {
//        $config = $this->getData('config');
//        $config['visible'] = ! (bool) $this->context->getRequestParam('id');
//        $this->setData('config', $config);
        parent::prepare();
    }
}
