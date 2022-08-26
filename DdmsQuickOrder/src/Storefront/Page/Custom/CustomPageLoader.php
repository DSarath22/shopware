<?php declare(strict_types=1);

namespace Ddms\QuickOrder\Storefront\Page\Custom;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomPageLoader
{
    /**
     * @var GenericPageLoaderInterface
     */
    private $genericPageLoader;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(GenericPageLoaderInterface $genericPageLoader, EventDispatcherInterface $eventDispatcher)
    {
        $this->genericPageLoader = $genericPageLoader;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function load(Request $request, SalesChannelContext $context): CustomPage
    {
        $page = $this->genericPageLoader->load($request, $context);
        $page = CustomPage::createFrom($page);

        // Do additional stuff, e.g. load more data from repositories and add it to page
         //$page->setExampleData(...);

        $this->eventDispatcher->dispatch(
            new CustomPageLoadedEvent($page, $context, $request)
        );

        return $page;
    }
}