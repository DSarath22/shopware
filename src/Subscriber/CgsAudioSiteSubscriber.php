<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Subscriber;

use Cgs\AudioSite\Storefront\Page\TestCustom\TestPageLoaderEvent;
use ONGR\ElasticsearchDSL\Serializer\Normalizer\OrderedNormalizerInterface;
use Shopware\Core\Checkout\Cart\CartEvents;
use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityWriter;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;


class CgsAudioSiteSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    protected $testRepository;

    public function __construct(EntityRepositoryInterface $testRepository)
    {
        $this->testRepository = $testRepository;
    }
    public static function getSubscribedEvents()
    {
        return [
            // CartEvents::CHECKOUT_ORDER_PLACED => 'onOrderPlaced'
            CheckoutCartPageLoadedEvent::class => 'onOrderPlaced',
            TestPageLoaderEvent::class => 'onTest',
            OrderEvents::ORDER_WRITTEN_EVENT => 'onOrder'
        ];
    }

    public function onOrder(EntityWrittenEvent $event)
    {

        $results = $event->getWriteResults();
        foreach ($results as $result) {
            $orderId = $result->getPayload()['id'];
            $order = $this->testRepository->search(
                (new Criteria())
                    ->addFilter(new EqualsFilter('orderId', $orderId))
                    ->addAssociation('customer'),
                Context::createDefaultContext()
            )->first();
            // dump($order);
            // die;
        }
    }

    public function onTest(TestPageLoaderEvent $event)
    {
        // dump($event);

        // die;
    }

    public function onOrderPlaced(CheckoutCartPageLoadedEvent $event)
    {
        $test = $this->testRepository->search(
            (new Criteria()),
            $event->getSalesChannelContext()->getContext()
        )->getElements();

        $event->getPage()->assign([
            'test' => $test
        ]);
    }
}
