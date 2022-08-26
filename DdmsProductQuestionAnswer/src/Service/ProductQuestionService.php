<?php

declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Ddms\ProductQuestionAnswer\Service\ProductMailService;


class ProductQuestionService
{
    /**
     * @var EntityRepositoryInterface
     */
    protected $questionRepository;

    /**
     * @var SystemConfigService
     */
    protected $systemConfigService;

    /**
     * @var ProductMailService
     */
    protected $productMailService;

    /**
     * @var EntityRepositoryInterface
     */
    protected $answerRepository;

    /**
     * @var EntityRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(
        EntityRepositoryInterface $questionRepository,
        SystemConfigService $systemConfigService,
        ProductMailService $productMailService,
        EntityRepositoryInterface $answerRepository,
        EntityRepositoryInterface $customerRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->systemConfigService = $systemConfigService;
        $this->productMailService = $productMailService;
        $this->answerRepository = $answerRepository;
        $this->customerRepository = $customerRepository;
    }

    public function saveQuestion(SalesChannelContext $context, Request $request)
    {
        $customer = $context->getCustomer();
        $error = false;
        $errorName = '';
        if ($customer) {
            $customerId = $customer->getId();
        }

        if (empty($request->request->get('question'))) {
            $error = true;
            $errorName = 'question';
        } else if (strlen($request->request->get('question')) > 225) {
            $error = true;
            $errorName = 'question-limit';
        }

        if (!$error) {
            $questionId = Uuid::randomHex();
            $questionDetail = [
                'id' => $questionId,
                'customerId' => $customerId,
                'productId' => $request->request->get('productId'),
                'question' => $request->request->get('question'),
                'viewStatus' => false
            ];

            $this->questionRepository->create([$questionDetail], Context::createDefaultContext());
            $questionDetail = $this->getQuestionById($questionId);
            $this->productMailService->questionMail($questionDetail, $context);
        }

        return [$error, $errorName];
    }

    public function getQuestion(String $productId, $limitQuestion, $offset, $answerOffset, $limitAnswer, $request)
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('productId', $productId),
            new EqualsFilter('status', true)
        );

        $criteria->addAssociation('product');
        $criteria->addAssociation('customer');
        $criteria->addAssociation('answer');
        $criteria->setLimit($limitQuestion);
        $criteria->setOffset($offset);
        $questionLists = $this->questionRepository->search(
            $criteria,
            Context::createDefaultContext()
        );

        $total = $this->questionRepository->search(
            (new Criteria())
                ->addFilter(
                    new EqualsFilter('productId', $productId),
                    new EqualsFilter('status', true)
                ),
            Context::createDefaultContext()
        )->getTotal();

        $list = [];

        if ($questionLists) {
            foreach ($questionLists as $questionList) {
                $answerDetail = [];
                $answerTotal = 0;
                $answerCriteria = new Criteria();;
                $answerCriteria->addFilter(
                    new EqualsFilter('questionId', $questionList->getId()),
                    new EqualsFilter('status', true)
                );
                $answerCriteria->addAssociation('question');
                $answerCriteria->setLimit($limitAnswer);
                $answerCriteria->setOffset($answerOffset);
                $answers = $this->answerRepository->search(
                    $answerCriteria,
                    Context::createDefaultContext()
                )->getElements();

                $answerTotal = $this->answerRepository->search(
                    (new Criteria())
                        ->addFilter(
                            new EqualsFilter('questionId', $questionList->getId()),
                            new EqualsFilter('status', true)
                        ),
                    Context::createDefaultContext()
                )->getTotal();

                if ($answers) {
                    foreach ($answers as $answer) {
                        $answerDetail[] = [
                            'id' => $answer->getId(),
                            'answer' => $answer->getAnswer(),
                            'answerBy' => $answer->getAnswerBy(),
                            'questionId' => $answer->getQuestionId()
                        ];
                    }
                }

                $list[] = [
                    'answer' => $answerDetail,
                    'question' => $questionList->getQuestion(),
                    'id' => $questionList->getId(),
                    'productId' => $questionList->getProductId(),
                    'answerTotal' => ceil($answerTotal / $limitAnswer)
                ];
            }
        }

        $question = [
            'list' => $list,
            'total' => ceil($total / $limitQuestion)
        ];

        return $question;
    }

    public function updateQuestionView($questionId)
    {
        $questionData = [
            'id' => $questionId,
            'viewStatus' => true
        ];

        $this->questionRepository->upsert([$questionData], Context::createDefaultContext());
    }

    public function getQuestionById($questionId)
    {
        return $this->questionRepository->search(
            (new Criteria())
                ->addFilter(new EqualsFilter('id', $questionId))
                ->addAssociation('product')
                ->addAssociation('customer')
                ->addAssociation('answer'),
            Context::createDefaultContext()
        )->first();
    }

    public function getCustomerIdByEmail($email)
    {
        $customer = $this->customerRepository->search(
            (new Criteria())
                ->addFilter(new EqualsFilter('email', $email)),
            Context::createDefaultContext()
        )->first();

        if ($customer) {
            return $customer->getId();
        } else {
            return null;
        }
    }
}
