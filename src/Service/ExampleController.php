<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Service;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItemFactoryRegistry;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Routing\StorefrontResponse;


/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class ExampleController extends StorefrontController
{
    private LineItemFactoryRegistry $factory;
    private CartService $cartService;

    public function __construct(LineItemFactoryRegistry $factory, CartService $cartService)
    {
        $this->$factory = $factory;
        $this->cartService = $cartService;
    }
    /**
     * @Route("/cartAdd", name="frontend.example", methods={"GET"})
     */
    public function add(Cart $cart, SalesChannelContext $context): StorefrontResponse
    {
        $lineItem = $this->factory->create([
            'type' => LineItem::PRODUCT_LINE_ITEM_TYPE,
            'referencedId' => '882ef339e8884bb4b0520241eca4ffb3',
            'quantity' => 5,
            'payload' => ['key' => 'value']
        ], $context);
        $this->cartService->add($cart, $lineItem, $context);
        return $this->renderStorefront('@Storefront/storefront/base.html.twig');
    }
}
