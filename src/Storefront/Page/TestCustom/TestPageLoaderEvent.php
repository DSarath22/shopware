<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Storefront\Page\TestCustom;

// use Cgs\AudioSite\Storefront\Page\TestCustom\TestExamplePage;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Cgs\AudioSite\Storefront\Page\TestCustom\TestExamplePage;

class TestPageLoaderEvent extends PageLoadedEvent
{
    protected TestExamplePage $page;
    public function __construct(TestExamplePage $page, SalesChannelContext $salesChannelContext, Request $request)
    {
        $this->page = $page;
        parent::__construct($salesChannelContext, $request);
    }
    public function getPage(): TestExamplePage
    {
        return $this->page;
    }
}
