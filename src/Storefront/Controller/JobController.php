<?php

declare(strict_types=1);

namespace Cgs\AudioSite\Storefront\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class JobController extends StorefrontController
{
    /**
     * @Route("/jobs", name="frontend.jobs", methods={"GET"})
     */
    public function showJobs()
    {
        return $this->renderStorefront('@CgsAudioSite/storefront/page/jobs.html.twig');
    }
}
