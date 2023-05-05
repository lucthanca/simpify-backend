<?php
declare(strict_types=1);

namespace SimiCart\SimpifyManagementGraphql\Model\App;

use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

class AppFilterAttributesForAst implements FieldEntityAttributesInterface
{
    /**
     * Map schema fields to entity attributes
     *
     * @var array
     */
    private $fieldMapping = [
        'uid' => 'entity_id',
        'shop_uid' => 'shop_id',
    ];

    /**
     * @var array
     */
    private $additionalFields = [];

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    public function __construct(
        ConfigInterface $config,
        array $additionalFields = [],
        array $attributeFieldMapping = []
    ) {
        $this->config = $config;
        $this->additionalFields = array_merge($this->additionalFields, $additionalFields);
        $this->fieldMapping = array_merge($this->fieldMapping, $attributeFieldMapping);
    }


    /**
     * Gather attributes for App filtering
     * Example format ['attributeNameInGraphQl' => ['type' => 'String'. 'fieldName' => 'attributeNameInSearchCriteria']]
     *
     * @return array
     */
    public function getEntityAttributes() : array
    {
        $appFilterInput = $this->config->getConfigElement('AppFilterInput');

        if (!$appFilterInput) {
            throw new \LogicException(__("AppFilterInput type not defined in schema."));
        }

        $fields = [];
        foreach ($appFilterInput->getFields() as $field) {
            $fields[$field->getName()] = [
                'type' => 'String',
                'fieldName' => $this->fieldMapping[$field->getName()] ?? $field->getName(),
            ];
        }

        foreach ($this->additionalFields as $additionalField) {
            $fields[$additionalField] = [
                'type' => 'String',
                'fieldName' => $additionalField,
            ];
        }

        return $fields;
    }
}
