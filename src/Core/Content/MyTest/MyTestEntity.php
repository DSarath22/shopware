<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Core\Content\MyTest;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class MyTestEntity extends Entity
{
    use EntityIdTrait;
    /**
     * @var string
     */
    protected $customerId;
    /**
     * @var bool
     */
    protected $status;

    /**
     * Get the value of customerId
     *
     * @return  string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set the value of customerId
     *
     * @param  string  $customerId
     *
     * @return  self
     */
    public function setCustomerId(string $customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  bool  $status
     *
     * @return  self
     */
    public function setStatus(bool $status)
    {
        $this->status = $status;

        return $this;
    }
}
