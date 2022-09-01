<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Storefront\Controller;

use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class ExampleController extends StorefrontController
{
    /**
     * @Route("/example", name="frontend.example.example", defaults={"XmlHttpRequest"=true}, methods={"GET"})
     */
    public function showExample(): JsonResponse
    {
        return new JsonResponse(['timestamp' => (new \DateTime())->format(\DateTimeInterface::W3C)]);
    }
}
