<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Core\Content\Question;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Product\ProductEntity;
use Ddms\ProductQuestionAnswer\Core\Content\Answer\AnswerEntity;

class QuestionEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var CustomerEntity
     */
    protected $customer;

    /**
     * @var string
     */
    protected $productId;

    /**
     * @var ProductEntity
     */
    protected $product;

    /**
     * @var string
     */
    protected $question;

    /**
     * @var bool
     */
    protected $status;

    /**
     * @var AnswerEntity
     */
    protected $answer;

    /**
     * @var bool
     */
    protected $viewStatus;


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
     * Get the value of customer
     *
     * @return  CustomerEntity
     */ 
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     *
     * @param  CustomerEntity  $customer
     *
     * @return  self
     */ 
    public function setCustomer(CustomerEntity $customer)
    {
        $this->customer = $customer;

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
     * Get the value of product
     *
     * @return  ProductEntity
     */ 
    public function getProduct()
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
     * Get the value of question
     *
     * @return  string
     */ 
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set the value of question
     *
     * @param  string  $question
     *
     * @return  self
     */ 
    public function setQuestion(string $question)
    {
        $this->question = $question;

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

    /**
     * Get the value of answer
     *
     * @return  AnswerEntity
     */ 
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the value of answer
     *
     * @param  AnswerEntity  $answer
     *
     * @return  self
     */ 
    public function setAnswer(AnswerEntity $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get the value of viewStatus
     *
     * @return  bool
     */ 
    public function getViewStatus()
    {
        return $this->viewStatus;
    }

    /**
     * Set the value of viewStatus
     *
     * @param  bool  $viewStatus
     *
     * @return  self
     */ 
    public function setViewStatus(bool $viewStatus)
    {
        $this->viewStatus = $viewStatus;

        return $this;
    }
}