<?php declare(strict_types=1);

namespace Ddms\ProductQuestionAnswer\Controller\Storefront;

use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Routing\Annotation\Route;
use Ddms\ProductQuestionAnswer\Service\ProductAnswerService;

/**
 * @RouteScope(scopes={"storefront"})
 */
class AnswerController extends StorefrontController
{
     /**
     * @var SystemConfigService
     */
    protected $systemConfigService;

    /**
     * @var ProductAnswerService
     */
    protected $productAnswerService;

    public function __construct(
        SystemConfigService $systemConfigService,
        ProductAnswerService $productAnswerService)
    {
        $this->systemConfigService = $systemConfigService;
        $this->productAnswerService = $productAnswerService;
    }

    /**
     * @Route("/qa/product/answer", name="frontend.detail.answer.save", defaults={"csrf_protected"=false})
     */
    public function saveAnswer(SalesChannelContext $context, Request $request): JsonResponse
    {   
        
        [$status, $error, $errorName] = $this->productAnswerService->saveAnswer($context, $request);
        return new JsonResponse([
            'status' => $status,
            'error' => $error,
            'errorName' => $errorName
        ]);
    }
}