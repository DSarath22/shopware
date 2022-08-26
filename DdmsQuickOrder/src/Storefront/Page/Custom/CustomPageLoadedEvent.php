<?php declare(strict_types=1);

namespace Ddms\QuickOrder\Storefront\Page\Custom;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class CustomPageLoadedEvent extends PageLoadedEvent
{
    /**
     * @var CustomPage
     */
    protected $page;

    public function __construct(CustomPage $page, SalesChannelContext $salesChannelContext, Request $request)
    {
        $this->page = $page;
        parent::__construct($salesChannelContext, $request);
    }

    public function getPage(): CustomPage
    {
        return $this->page;
    }
}