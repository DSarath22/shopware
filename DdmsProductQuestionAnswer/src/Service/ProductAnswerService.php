<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Ddms\ProductQuestionAnswer\Service\ProductQuestionService;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Ddms\ProductQuestionAnswer\Service\ProductMailService;

class ProductAnswerService
{
    /**
     * @var EntityRepositoryInterface
     */
    protected $answerRepository;

    /**
     * @var ProductQuestionService
     */
    protected $productQuestionService;

    /**
     * @var SystemConfigService
     */
    protected $systemConfigService;

    /**
     * @var ProductMailService
     */
    protected $productMailService;

    public function __construct(
        EntityRepositoryInterface $answerRepository,
        ProductQuestionService $productQuestionService,
        SystemConfigService $systemConfigService,
        ProductMailService $productMailService
    )
    {
        $this->answerRepository = $answerRepository;
        $this->productQuestionService = $productQuestionService;
        $this->systemConfigService = $systemConfigService;
        $this->productMailService = $productMailService;
    }

    public function saveAnswer(SalesChannelContext $context, Request $request): array
    {
        $customer = $context->getCustomer();
        $questionId = $request->request->get('questionId');
        $answer = $request->request->get('answer');
        $error = false;
        $errorName = '';

        if (empty($request->request->get('answer'))) {
            $error = true;
            $errorName = 'answer';
        } else if (strlen($request->request->get('answer')) > 255 ) {
            $error = true;
            $errorName = 'answer-limit';
        }
        
        if (!$error) {
            $answerId = Uuid::randomHex();
            $answerDetail = [
                'id' => $answerId,
                'questionId' => $questionId,
                'answer' => $answer,
                'answerBy' => $customer->getFirstName(),
                'status' => false
            ];
    
            $this->productQuestionService->updateQuestionView($questionId);
            $this->answerRepository->create([$answerDetail], Context::createDefaultContext());
            $answerDetail = $this->getAnswerById($answerId);
            $this->productMailService->answerMail($answerDetail, $context);
        }

        return [false, $error, $errorName];
    }

    private function getAnswerById($id) {
        return $this->answerRepository->search(
            (new Criteria())
            ->addFilter(new EqualsFilter('id', $id))
            ->addAssociation('question')
            ->addAssociation('question.product'),
            Context::createDefaultContext()
        )->first();
    }

}


