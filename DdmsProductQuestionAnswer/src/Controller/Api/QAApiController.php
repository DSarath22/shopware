<?php declare(strict_types=1);

namespace Webkul\QA\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\System\SystemConfig\SystemConfigService;

/**
 * @RouteScope(scopes={"api"})
 */

class QAApiController extends AbstractController
{   
    /**
    * @var SystemConfigService
    */
    protected $systemConfigService;

    public function __construct(
        SystemConfigService $systemConfigService
    )
    {
        $this->systemConfigService = $systemConfigService;
    }

    /**
    * @Route("/api/qa/save/config", name="api.qa.save.config")
    *
    */
    public function saveConfig(Request $request): JsonResponse
    {
        $configValues = $request->request->get('config');

        if ($configValues) {
            foreach ($configValues as $key => $configValue) {
                $this->systemConfigService->set('WebkulQA.config.' . $key, $configValue);
            }
        }

        return new JsonResponse(true);
    }
}