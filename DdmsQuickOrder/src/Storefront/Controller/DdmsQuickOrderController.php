<?php

declare(strict_types=1);

namespace Ddms\QuickOrder\Storefront\Controller;

use Ddms\QuickOrder\Storefront\Page\Custom\CustomPageLoader;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItemFactoryRegistry;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\LoginRequired;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Shopware\Core\Content\Product\Exception\ProductNotFoundException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingRoute;

/**
 * @RouteScope(scopes={"storefront"})
 */
class DdmsQuickOrderController extends StorefrontController
{
    /**
     * @var CustomPageLoader
     */
    private $customPageLoader;
    /**
     * @var CartService
     */
    private $cartService;
    /**
     * @var LineItemFactoryRegistry
     */
    private $lineItemFactoryRegistry;


    public function __construct(CustomPageLoader $customPageLoader, CartService $cartService, LineItemFactoryRegistry $lineItemFactoryRegistry)
    {
        $this->customPageLoader = $customPageLoader;
        $this->cartService = $cartService;
        $this->lineItemFactoryRegistry = $lineItemFactoryRegistry;
    }
    /**
     * @LoginRequired
     * @Route("account/quick/order", name="frontend.account.quick.order", methods={"GET","POST"})
     */
    public function quickOrderPage(Request $request, SalesChannelContext $context, Cart $cart): Response
    {
        $page = $this->customPageLoader->load($request, $context);
        if ($request->getMethod() == 'GET') {
            return $this->renderStorefront('@SampleTask/storefront/page/custom/index.html.twig', [
                'page' => $page,
            ]);
        } else {
            $products = $request->request->get('product');
            $count = 0;
            foreach ($products as $key=>$product) {
                
                if($product['articleNo'] && $product['quantity']) {
        
                    $productEntity = $this->container->get('product.repository')->search((new Criteria())->addFilter(new EqualsFilter('productNumber', $product['articleNo']))->addFilter(new EqualsFilter('active', 1)), $context->getContext());
                    if ($productEntity->getTotal() == 0) {
                        $this->addFlash(self::DANGER, number_format((float)$key, 2) .' '. $product['articleNo'].' - '. $this->trans('ddmsQuickOrder.message.itemNotFound'));
                    } elseif($productEntity->first()->getChildCount() >0) {
                        $this->addFlash(self::DANGER, number_format((float)$key, 2) .' '. $product['articleNo'].' - '. $this->trans('ddmsQuickOrder.message.parentItem'));
                    } else {
                        $productId = $productEntity->first()->getId();
                        $lineItem = new LineItem(
                            $productId,
                            LineItem::PRODUCT_LINE_ITEM_TYPE,
                            $productId,
                            (int)$product['quantity']
                        );
                        $lineItem->setStackable(true);
                        $lineItem->setRemovable(true);
                
                        $cart = $this->cartService->add($cart, $lineItem, $context);
                        $count++;
                        
                    }
                } 
            }
            if ($count >0) {
                $this->addFlash(self::SUCCESS, $count.' '. $this->trans('ddmsQuickOrder.message.successMessage'));
            }            
            return new RedirectResponse($this->generateUrl('frontend.account.quick.order'));
            
        }
    }

    /**
     * @Route("storefront/fetch/product/name", name="frontend.product.name", methods={"POST"}, defaults={"csrf_protected"=false})
     */
    public function fetchProductNameByNumber(Request $request, SalesChannelContext $salesChannelContext){
       $productNumber = $request->request->get('productNumber');
       $productRepository = $this->container->get('product.repository');
       $criteria = new Criteria();
       $criteria->addFilter(new EqualsFilter('productNumber',$productNumber))->addAssociation('options')->addAssociation('options.group');
       $productEntity = $productRepository->search($criteria,$salesChannelContext->getContext())->first();
      
       if ($productEntity) {
           $productName = $productEntity->getTranslated()['name'];
           return new JsonResponse(['productName'=>$productName,'variation'=>$productEntity->getVariation()]);
       } else {
           return new JsonResponse(false);
       }

    }
    /**
     * @Route("storefront/quickorder/bulk", name="frontend.quickorder.bulk", methods={"POST"})
     */
    public function addMultiProductToCart(Request $request, SalesChannelContext $salesChannelContext, Cart $cart)
    {
        $data = $request->request->get('tableitem');
        
        $arrayData = explode(PHP_EOL,$data);
        foreach($arrayData as $key=>$row) {
            if(empty($arrayData[count($arrayData)-1])) {
                unset($arrayData[count($arrayData)-1]);
            }
        }
        foreach ($arrayData as $row) {
            $newArray[]= preg_split("/\t|;|,|\s+/",  preg_replace('/\r|"/', '', $row));
        }
        $error = array();
        $success = null;
        $count = 0;
        foreach($newArray as $key=>$row) {
            if(empty(trim($row[0]))) {
                unset($newArray[$key]);
            }
        }
        foreach($newArray as $key=>$row) {
            if(sizeof($row) < 2) {
                $this->addFlash(self::DANGER, number_format((float)$key, 2) . ' - '. $this->trans('ddmsQuickOrder.message.invalidFormat'));
                unset($newArray[$key]);
            }
        }
        
        $newArray = array_values($newArray);
        foreach ($newArray as $key=>$row) {
            if(count($row) <2){
                $this->addFlash(self::DANGER, number_format((float)$key, 2) . ' - '. $this->trans('ddmsQuickOrder.message.itemNumberMissing'));
            } else {
    
                $productEntity = $this->container->get('product.repository')->search((new Criteria())->addFilter(new EqualsFilter('productNumber', $row[1]))->addFilter(new EqualsFilter('active', 1)), $salesChannelContext->getContext());
                if ($productEntity->getTotal() == 0) {
                    $this->addFlash(self::DANGER, number_format((float)$key, 2) .' '. $row[1].' - '. $this->trans('ddmsQuickOrder.message.itemNotFound'));
                } elseif($productEntity->first()->getChildCount() >0) {
                    $this->addFlash(self::DANGER, number_format((float)$key, 2) .' '. $row[1].' - '. $this->trans('ddmsQuickOrder.message.parentItem'));
                } else {
                    $productId = $productEntity->first()->getId();
                    $lineItem = new LineItem(
                        $productId,
                        LineItem::PRODUCT_LINE_ITEM_TYPE,
                        $productId,
                        (int)$row[0]
                    );
                    $lineItem->setStackable(true);
                    $lineItem->setRemovable(true);
            
                    $cart = $this->cartService->add($cart, $lineItem, $salesChannelContext);
                    $count++;
                    
                }
            }
        }
        if ($count >0) {
            $this->addFlash(self::SUCCESS, $count.' '. $this->trans('ddmsQuickOrder.message.successMessage'));
        }
        return new RedirectResponse($this->generateUrl('frontend.account.quick.order'));

    }

}
