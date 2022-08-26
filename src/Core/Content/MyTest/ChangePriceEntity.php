<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Core\Content\MyTest;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class ChangePriceEntity
{
    use EntityIdTrait;
    /**
     * @var ProductEntity
     */
    protected $product;
    /**
     * @var string
     */
    protected $productId;
    /**
     * @var float
     */
    protected $price;

    /**
     * Get the value of product
     *
     * @return  ProductEntity
     */
    public function getProduct(): ProductEntity
    {
        return $this->product;
    }

    /**
     * Set the value of product
     *
     * @param  ProductEntity  $product
     *
     * @return  self
     */
    public function setProduct(ProductEntity $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of productId
     *
     * @return  string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set the value of productId
     *
     * @param  string  $productId
     *
     * @return  self
     */
    public function setProductId(string $productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get the value of price
     *
     * @return  float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  float  $price
     *
     * @return  self
     */
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }
}
