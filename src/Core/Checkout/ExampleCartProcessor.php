<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Cgs\AudioSite\Service\ExampleHandler;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;

class ExampleCartProcessor implements CartProcessorInterface
{
    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original->getLineItems()->filterFlatByType(ExampleHandler::TYPE);
        foreach ($lineItems as $lineItem) {
            $toCalculate->add($lineItem);
        }
    }
}
