<?php declare(strict_types=1);

namespace Ddms\AdditionalFields;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class DdmsAdditionalFields extends Plugin
{
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }
        $connection = $this->container->get(Connection::class); 
        $connection->executeUpdate("DELETE FROM `custom_field_set` WHERE name = 'additional_fields_categories' "); 
        $connection->executeUpdate("DELETE FROM `custom_field_set` WHERE name = 'additional_fields_product' "); 
        $connection->executeUpdate("DELETE FROM `custom_field` WHERE name = 'additional_fields_product_title' ");    
        $connection->executeUpdate("DELETE FROM `custom_field` WHERE name = 'additional_fields_categories_description' "); 
        $connection->executeUpdate("DELETE FROM `custom_field` WHERE name = 'additional_fields_categories_title' "); 
        $connection->executeUpdate("DELETE FROM `custom_field` WHERE name = 'additional_fields_product_description' "); 
    }

    public function install(InstallContext $installContext): void
    {
        $customFieldRepository = $this->container->get('custom_field_set.repository');
        $customFieldProductData = $customFieldRepository->search((new Criteria())->addFilter(new EqualsFilter('name','additional_fields_product')), $installContext->getContext())->first();
        $customFieldCategoryData = $customFieldRepository->search((new Criteria())->addFilter(new EqualsFilter('name','additional_fields_categories')), $installContext->getContext())->first();
        if (!$customFieldProductData) {
            $customFieldRepository->create([
                [
                    'name' => 'additional_fields_product',
                    'config' => [
                        'label' => [
                            'en-GB' => 'Additional Fields Product',
                            'de-DE' => 'Zusatzfelder Produkt'
                        ]
                    ],
                    'customFields' => [
                        [
                            'name' => 'additional_fields_product_title',
                            'type' => CustomFieldTypes::TEXT,
                            'config' => [
                                'label' => [
                                'en-GB' => 'Title',
                                'de-DE' => 'Titel'
                                ],
                                'type' => 'text',
                                'componentName'=> 'sw-field',
                                'customFieldType'=> 'text',
                                'customFieldPosition' => (int)1
                            ]
                        ],
                        [
                            'name' => 'additional_fields_product_description',
                            'type' => CustomFieldTypes::HTML,
                            'config' => [
                                'label' => [
                                'en-GB' => 'Description',
                                'de-DE' => 'Beschreibung'
                                ],
                                'type' => 'html',
                                'componentName'=> 'sw-text-editor',
                                'customFieldPosition' => (int)2
                            ]
                        ]

                    ],
                
                    'relations' => [
                        [
                        'entityName' => 'product'
                        ]
                    ]
                ]
            ], $installContext->getContext());
        }


        if (!$customFieldCategoryData) {
            $customFieldRepository->create([
                [
                    'name' => 'additional_fields_categories',
                    'config' => [
                        'label' => [
                            'en-GB' => 'Additional Fields Categories',
                            'de-DE' => 'Zusätzliche Felder Kategorien'
                        ]
                    ],
                    'customFields' => [
                        [
                            'name' => 'additional_fields_categories_title',
                            'type' => CustomFieldTypes::TEXT,
                            'config' => [
                                'label' => [
                                'en-GB' => 'Title',
                                'de-DE' => 'Titel'
                                ],
                                'type' => 'text',
                                'componentName'=> 'sw-field',
                                'customFieldType'=> 'text',
                                'customFieldPosition' => (int)1
                            ]
                        ],
                        [
                            'name' => 'additional_fields_categories_description',
                            'type' => CustomFieldTypes::HTML,
                            'config' => [
                                'label' => [
                                'en-GB' => 'Description',
                                'de-DE' => 'Beschreibung'
                                ],
                                'type' => 'html',
                                'componentName'=> 'sw-text-editor',
                                'customFieldPosition' => (int)2
                            ]
                        ]

                    ],
                
                    'relations' => [
                        [
                        'entityName' => 'category'
                        ]
                    ]
                ]
            ], $installContext->getContext());
        }
    }
} 