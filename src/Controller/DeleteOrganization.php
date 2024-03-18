<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizations/{id}', requirements: ['id' => '[0-9a-f-]+'], methods: ['DELETE'])]
class DeleteOrganization extends Api
{
    public function __construct(
        private readonly OrganizationRepository $organizationRepository
    ) {}

    public function __invoke(Organization $organization): Response
    {
        $this->denyAccessUnlessGranted(Verb::DELETE, $organization);

        try {
            $this->organizationRepository->delete($organization);

            return new Response();
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
