<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Storefront\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Cgs\AudioSite\Storefront\Page\TestCustom\TestPageLoader;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class TestController extends StorefrontController
{
    private TestPageLoader $testPageLoader;
    public function __construct(TestPageLoader $testPageLoader)
    {
        $this->testPageLoader = $testPageLoader;
    }
    /**
     * @Route("/test", name="frontend.test", methods={"GET"})
     */
    public function testPage(Request $request, SalesChannelContext $context)
    {
        $page = $this->testPageLoader->load($request, $context);
        $customerList = $this->testPageLoader->getCustomerListData($request, $context);
        return $this->renderStorefront('@CgsAudioSite/storefront/page/test.html.twig', [
            'page' =>  $page,
            'customerList' => $customerList
        ]);
    }
    /**
     * @Route("/save/data", name="frontend.save.data", methods={"GET", "POST"})
     */

    public function saveData(Request $request, SalesChannelContext $context)
    {
        $status = $this->testPageLoader->savedb($request, $context);
        if ($status) {
            $this->addFlash(self::SUCCESS, 'saved data');
        } else {
            $this->addFlash(self::DANGER, 'data is not saved');
        }
        return $this->redirectToRoute('frontend.test');
    }
}
