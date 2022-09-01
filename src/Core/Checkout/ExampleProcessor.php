<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\Rule\LineItemRule;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;

class ExampleProcessor implements CartProcessorInterface
{
    /**
     * @var PercentagePriceCalculator
     */
    private $calculator;

    public function __construct(PercentagePriceCalculator $calculator)
    {
        $this->calculator = $calculator;
    }
    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        // this the part where we check the item is present inside the cart or not
        $products = $this->exampleProducts($toCalculate);

        if ($products->count() === 0) {
            return;
        }

        $discountLineItem = $this->createDiscount('20% discount');

        // -10 indicates the 10% discount will be applied
        $definition = new PercentagePriceDefinition(
            -10,
            new LineItemRule(LineItemRule::OPERATOR_EQ, $products->getkeys())
        );
        $discountLineItem->setPriceDefinition($definition);

        $discountLineItem->setPrice($this->calculator->calculate($definition->getPercentage(), $products->getPrices(), $context));
        $toCalculate->add($discountLineItem);
    }
    public function exampleProducts(Cart $cart): LineItemCollection
    {
        return $cart->getLineItems()->filter(function (LineItem $item) {
            if ($item->getType() !== LineItem::PRODUCT_LINE_ITEM_TYPE) {
                return false;
            }
            // changed the getLabel() to getId and passed the Id statically from db to get the discount
            $exampleInLabel = stripos($item->getId(), '035A3429045E4FEDBAAEF71B887E0723') !== false;

            if (!$exampleInLabel) {
                return false;
            }

            return $item;
        });
    }
    // this part will display the applied dicount lable in the frontend.
    public function createDiscount(string $name): LineItem
    {
        $discountLineItem = new LineItem($name, '20% discount', null, 1);

        $discountLineItem->setLabel('20% discount');
        $discountLineItem->setGood(false);
        $discountLineItem->setStackable(false);
        $discountLineItem->setRemovable(false);

        return $discountLineItem;
    }
}
