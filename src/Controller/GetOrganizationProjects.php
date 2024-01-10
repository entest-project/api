<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Manager\OrganizationManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/organizations/{slug}/projects', requirements: ['id' => '[0-9a-z-]+'], methods: ['GET'])]
class GetOrganizationProjects extends Api
{
    public function __construct(
        private readonly OrganizationManager $organizationManager
    ) {}

    public function __invoke(Organization $organization): Response
    {
        return $this->buildSerializedResponse(
            $this->organizationManager->getOrganizationProjects($organization, $this->getUser()),
            'LIST_PROJECTS'
        );
    }
}
