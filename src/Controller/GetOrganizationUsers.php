<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationUserRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations/{slug}/users", methods={"GET"}, requirements={"organizationSlug": "[0-9a-z-]+")
 */
class GetOrganizationUsers extends Api
{
    private OrganizationUserRepository $organizationUserRepository;

    public function __construct(OrganizationUserRepository $organizationUserRepository)
    {
        $this->organizationUserRepository = $organizationUserRepository;
    }

    public function __invoke(Organization $organization): Response
    {
        $this->denyAccessUnlessGranted(Verb::LIST_USERS, $organization);

        $users = $this->organizationUserRepository->findBy(['organization' => $organization]);

        return $this->buildSerializedResponse($users, 'LIST_ORGANIZATION_USERS');
    }
}
