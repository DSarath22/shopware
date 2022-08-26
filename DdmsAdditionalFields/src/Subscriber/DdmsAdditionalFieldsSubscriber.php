<?php declare(strict_types=1);

namespace Ddms\AdditionalFields\Subscriber;

use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelEntityLoadedEvent;
use Shopware\Storefront\Page\Navigation\NavigationPageLoadedEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Storefront\Page\GenericPageLoadedEvent;

class DdmsAdditionalFieldsSubscriber implements EventSubscriberInterface
{   

    public static function getSubscribedEvents(): array
    {
        return [
            ProductPageLoadedEvent::class => 'updateProductPage',
            'sales_channel.'. ProductEvents::PRODUCT_LOADED_EVENT => 'salesChannelLoadedProduct',
            NavigationPageLoadedEvent::class => 'updateblock'            
        ];
    }

    public function updateblock(NavigationPageLoadedEvent $event) {
        
        $customFields = $event->getPage()->getHeader()->getNavigation()->getActive()->getCustomFields();
        if ($event->getPage()->getCmsPage()) {
            foreach($event->getPage()->getCmsPage()->getSections()->getElements() as $section) {
                foreach($section->getBlocks()->getElements() as $block) {
                    foreach($block->getSlots()->getElements() as $slot) {
                        if ($slot->getType() == 'text') {
                            if ($slot->getConfig()['content']['value'] == 'category.description' )
                            {
                                if (array_key_exists('additional_fields_categories_description', $customFields) && !empty($customFields['additional_fields_categories_description'])) {
                                    $slot->getData()->setContent($customFields['additional_fields_categories_description']);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if ($customFields) {
            $translatedData = $event->getPage()->getHeader()->getNavigation()->getActive()->getTranslated();
            $updateTranslated = [
                'breadcrumb' => $translatedData['breadcrumb'],
                'name' => isset($customFields['additional_fields_categories_title']) ? $customFields['additional_fields_categories_title'] : $translatedData['name'],
                "customFields" => $translatedData['customFields'],
                "slotConfig" => $translatedData['slotConfig'],
                "linkType" => $translatedData['linkType'],
                "internalLink" => $translatedData['internalLink'],
                "externalLink" => $translatedData['externalLink'],
                "linkNewTab" => $translatedData['linkNewTab'],
                "description" =>  isset($customFields['additional_fields_categories_description']) ? $customFields['additional_fields_categories_description'] : $translatedData['description'],
                "metaTitle" => isset($customFields['additional_fields_categories_title']) ? $customFields['additional_fields_categories_title'] : $translatedData['name'],
                "metaDescription" => isset($customFields['additional_fields_categories_description']) ? $customFields['additional_fields_categories_description'] : $translatedData['description'],
                "keywords" => $translatedData['keywords']
            ];
            
            $event->getPage()->getHeader()->getNavigation()->getActive()->setTranslated($updateTranslated);
          
            $event->getPage()->getMetaInformation()->setMetaTitle(isset($customFields['additional_fields_categories_title']) ? $customFields['additional_fields_categories_title'] :  $event->getPage()->getMetaInformation()->getMetaTitle());

            $event->getPage()->getMetaInformation()->setMetaDescription(isset($customFields['additional_fields_categories_description']) ? $customFields['additional_fields_categories_description'] :$event->getPage()->getMetaInformation()->getMetaDescription());
            
        }
    }

    public function salesChannelLoadedProduct(SalesChannelEntityLoadedEvent $event) {
        foreach($event->getEntities() as $product) {
            if ($product->getCustomFields()) {
                $this->updateProductDetail($product);
            }
        }
    }

    public function updateProductDetail($product) {
        $customName = '';
        $customDescription = '';
        if (isset($product->getCustomFields()['additional_fields_product_title'])) {
            $customName = $product->getCustomFields()['additional_fields_product_title'];
        }

        if (isset($product->getCustomFields()['additional_fields_product_description'])) {
            $customDescription = $product->getCustomFields()['additional_fields_product_description'];
        }

        $updateTranslated = [
            'metaDescription' => $customDescription ? $customDescription : $product->getTranslated()['description'],
            'name' => $customName ? $customName : $product->getTranslated()['name'],
            'keywords' => $product->getTranslated()['keywords'],
            'description' => $customDescription ? $customDescription : $product->getTranslated()['description'],
            'metaTitle' => $customName ? $customName : $product->getTranslated()['name'],
            'packUnit' => $product->getTranslated()['packUnit'],
            'packUnitPlural' => $product->getTranslated()['packUnitPlural'],
            'customFields' => $product->getTranslated()['customFields'],
            'slotConfig' => $product->getTranslated()['slotConfig'],
            'customSearchKeywords' => $product->getTranslated()['customSearchKeywords']
        ];

        $product->setTranslated($updateTranslated);
       
        $product->setMetaTitle($customName ? $customName : $product->getTranslated()['name']);
        $product->setMetaDescription($customDescription ? $customDescription : $product->getTranslated()['description']);
       
    }

    public function updateProductPage(ProductPageLoadedEvent $event) {
        $customDescription = '';
        if ($product = $event->getPage()->getProduct()) {
            if ($product->getCustomFields()) {
               $this->updateProductDetail($product);
            }
           
            if (array_key_exists('additional_fields_product_description', $product->getCustomFields())) {
                $customDescription = $product->getCustomFields()['additional_fields_product_description'];
            }

            if ($event->getPage()->getCmsPage()) {
                foreach($event->getPage()->getCmsPage()->getSections()->getElements() as $section) {
                    foreach($section->getBlocks()->getElements() as $block) {
                        foreach($block->getSlots()->getElements() as $slot) {
                            if ($slot->getType() == 'text') {
                                if ($slot->getConfig()['content']['value'] == 'product.description' )
                                {
                                    if (!empty($customDescription)) {
                                        $slot->getData()->setContent($product->getCustomFields()['additional_fields_product_description']);
                                    }
                                }
                            }
                           
                        }
                    }
                    
                }
            }
        }      


    }
    
}