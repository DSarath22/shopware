<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Storefront\Page\TestCustom;

use Shopware\Storefront\Page\Page;
use Cgs\AudioSite\Core\Content\MyTest\MyTestEntity;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;

class TestExamplePage extends Page
{
    protected MyTestEntity $tesData;
    protected StorefrontSearchResult $customer;

    public function getTesData(): MyTestEntity
    {
        return $this->tesData;
    }


    public function setTesData($tesData)
    {
        $this->tesData = $tesData;
        return $this;
    }


    /**
     * Get the value of customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     *
     * @return  self
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }
}
