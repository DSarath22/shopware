<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Core\Checkout\Cart;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use \Shopware\Core\Checkout\Cart\CartDataCollectorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;

class PriceOverwrite implements CartDataCollectorInterface, CartProcessorInterface
{

    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;
    /**
     * @var QuantityPriceCalculator
     */
    private $calculator;


    public function __construct(QuantityPriceCalculator $calculator)
    {
        // $this->productRepository = $productRepository;
        $this->calculator = $calculator;
    }
    public function collect(CartDataCollection $data, Cart $original, SalesChannelContext $context, CartBehavior $behavious): void
    {
        $productIds = $original->getLineItems()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE)->getReferenceIds();

        $filtered = $this->filterFetchedPrices($productIds, $data);
        if (empty($filtered)) {
            return;
        }
        // $criteria = new Criteria();
        // $criteria->addFilter(new EqualsAnyFilter('productId', $filtered));
        // $prices = 222;
        // dump($prices);
        // die;
        foreach ($filtered as $id) {
            $key = $this->buildKey($id);
            $prices = 222;
            // $price = null;
            // foreach ($prices as $current) {
            //     if ($current->getProductId() === $id) {
            //         $price = $current;
            //         break;
            //     }
            // }
            $data->set($key, $prices);
        }
    }

    public function filterFetchedPrices(array $productIds, CartDataCollection $data): array
    {
        $filtered = [];
        foreach ($productIds as $id) {
            $key = $this->buildKey($id);
            if ($data->has($key)) {
                continue;
            }
            $filtered[] = $id;
        }
        return $filtered;
    }
    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavious): void
    {
        $products = $toCalculate->getLineItems()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE)->getReferenceIds();
        foreach ($products as $product) {
            $key = $this->buildKey($product->getReferencedId());
            if (!$data->has($key) || $data->get($key) === null) {
                continue;
            }
            $price = $data->get($key);
            $definition = new QuantityPriceDefinition(
                $price,
                $product->getPrice()->getTaxRules(),
                $product->getPrice()->getQuantity()
            );
            $calculated = $this->calculator->calculate($definition, $context);
            $product->setPrice($calculated);
            $product->setPriceDefinition($definition);
        }
    }
    public function buildKey(string $id): string
    {
        return 'price-overwrite-' . $id;
    }
}
