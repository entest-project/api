<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Manager\OrganizationManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations/{slug}/projects", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetOrganizationProjects extends Api
{
    private OrganizationManager $organizationManager;

    public function __construct(OrganizationManager $organizationManager)
    {
        $this->organizationManager = $organizationManager;
    }

    public function __invoke(Organization $organization): Response
    {
        return $this->buildSerializedResponse(
            $this->organizationManager->getOrganizationProjects($organization, $this->getUser()),
            'LIST_PROJECTS'
        );
    }
}
