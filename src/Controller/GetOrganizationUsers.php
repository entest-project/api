<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationUserRepository;
use App\Security\Voter\Verb;
use App\Serializer\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizations/{slug}/users', requirements: ['slug' => '[0-9a-z-]+'], methods: ['GET'])]
class GetOrganizationUsers extends Api
{
    public function __construct(
        private readonly OrganizationUserRepository $organizationUserRepository
    ) {}

    public function __invoke(Organization $organization): Response
    {
        $this->denyAccessUnlessGranted(Verb::LIST_USERS, $organization);

        $users = $this->organizationUserRepository->findBy(['organization' => $organization]);

        return $this->buildSerializedResponse($users, Groups::ListOrganizationUsers);
    }
}
