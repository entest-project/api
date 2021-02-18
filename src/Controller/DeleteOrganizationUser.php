<?php

namespace App\Controller;

use App\Entity\OrganizationUser;
use App\Repository\OrganizationUserRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations/{organization}/users/{user}", methods={"DELETE"}, requirements={"organization": "[a-f0-9-]+", "user": "[a-f0-9-]+"})
 */
class DeleteOrganizationUser extends Api
{
    private OrganizationUserRepository $organizationUserRepository;

    public function __construct(OrganizationUserRepository $organizationUserRepository)
    {
        $this->organizationUserRepository = $organizationUserRepository;
    }

    public function __invoke(OrganizationUser $organizationUser, Request $request): Response
    {
        $this->denyAccessUnlessGranted(Verb::DELETE, $organizationUser);

        try {
            $this->organizationUserRepository->delete($organizationUser);

            return new JsonResponse();
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
