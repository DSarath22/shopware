<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Storefront\Page\TestCustom;

// use Cgs\AudioSite\Storefront\Page\TestCustom\TestExamplePage;


use Cgs\AudioSite\Service\TestDataService;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Cgs\AudioSite\Storefront\Page\TestCustom\TestExamplePage;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Cgs\AudioSite\Storefront\Page\TestCustom\TestPageLoaderEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;


class TestPageLoader
{
    private GenericPageLoaderInterface $genericPageLoader;
    private EventDispatcherInterface $eventDispatcher;
    private EntityRepositoryInterface $customerRepository;
    private TestDataService $testDataService;


    public function __construct(GenericPageLoaderInterface $genericPageLoader, EventDispatcherInterface $eventDispatcher, EntityRepositoryInterface $customerRepository, TestDataService $testDataService)
    {
        $this->genericPageLoader = $genericPageLoader;
        $this->eventDispatcher = $eventDispatcher;
        $this->customerRepository = $customerRepository;
        $this->testDataService = $testDataService;
    }
    public function load(Request $request, SalesChannelContext $context): TestExamplePage
    {
        $page = $this->genericPageLoader->load($request, $context);

        $page = TestExamplePage::createFrom($page);
        $customers = $this->customerRepository->search(
            (new Criteria()),
            $context->getContext()
        );

        $page->setCustomer(StorefrontSearchResult::createFrom($customers));
        $this->eventDispatcher->dispatch(
            new TestPageLoaderEvent($page, $context, $request)
        );
        return $page;
    }
    public function savedb(Request $request, SalesChannelContext $context)
    {
        $sd = $this->testDataService->getDatalist($request, $context);

        return $sd;
    }
    public function getCustomerListData(Request $request, SalesChannelContext $context)
    {
        $listData=$this->testDataService->displayTabel($request, $context);
        return $listData;
    }
}
