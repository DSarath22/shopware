<?php

declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Controller\Storefront;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Storefront\Controller\StorefrontController;
use Ddms\ProductQuestionAnswer\Service\ProductQuestionService;

/**
 * @RouteScope(scopes={"storefront"})
 */
class QuestionController extends StorefrontController
{
    /**
     * @var SystemConfigService
     */
    protected $systemConfigService;

    /**
     * @var ProductQuestionService
     */
    protected $productQuestionService;

    public function __construct(
        SystemConfigService $systemConfigService,
        ProductQuestionService $productQuestionService
    ) {
        $this->systemConfigService = $systemConfigService;
        $this->productQuestionService = $productQuestionService;
    }

    /**
     * @Route("/qa/product/question", name="frontend.detail.question.save", methods={"POST"}, defaults={"csrf_protected"=false})
     */
    public function saveQuestion(SalesChannelContext $context, Request $request): JsonResponse
    {
        [$error, $errorName] = $this->productQuestionService->saveQuestion($context, $request);

        return new JsonResponse([
            'error' => $error,
            'errorName' => $errorName
        ]);
    }

    /**
     * @Route("/qa/question/list/{productId}", name="qa.question.list", defaults={"csrf_protected"=false} )
     */
    public function getQuestionList(SalesChannelContext $context, Request $request, String $productId)
    {
        $query = $request->query->all();
        $page = $query['page'];
        $offset = ($page - 1) * 5;
        $answerPage = $query['answerPage'];
        $answerOffset = ($answerPage - 1) * 5;
        $list = $this->productQuestionService->getQuestion($productId, 5, $offset, $answerOffset, 5, $request);
        return new JsonResponse($list);
    }
}
