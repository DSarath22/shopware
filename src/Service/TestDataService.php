<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Service;

use Shopware\Core\Framework\Uuid\Uuid;
use Cgs\AudioSite\Storefront\Page\TestCustom\TestPageLoaderEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class TestDataService
{
    protected EntityRepositoryInterface $testRepository;

    public function __construct(EntityRepositoryInterface $testRepository)
    {
        $this->testRepository = $testRepository;
    }

    public function getDatalist($request, $context)
    {
        $customerId = $request->request->get('customerId');
        $radio = $request->request->get('status');

        $status = false;
        if ($radio === 'checkedIn') {
            $status = true;
        }
        $saveData = [
            'id' => Uuid::randomHex(),
            'customerId' => $customerId,
            'status' => $status
        ];

        try {
            $this->testRepository->create([$saveData], $context->getContext());
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }

        return $success;
    }
    public function displayTabel($request, $context)
    {
        $customerList = $this->testRepository->search((new Criteria()),
            $context->getContext()
        );
        return $customerList;
    }
}
