<?php

namespace App\Controller;

use App\Entity\OrganizationUser;
use App\Helper\RequestHelper;
use App\Manager\OrganizationUserManager;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/organizations/{organization}/users/{user}', requirements: ['project' => '[a-f0-9-]+', 'user' => '[a-f0-9-]+'], methods: ['PUT'])]
class EditOrganizationUser extends Api
{
    public function __construct(
        private readonly OrganizationUserManager $organizationUserManager
    ) {}

    public function __invoke(OrganizationUser $organizationUser, Request $request): Response
    {
        $this->denyAccessUnlessGranted(Verb::UPDATE, $organizationUser);

        try {
            $this->organizationUserManager->changePermissions($organizationUser, RequestHelper::extractFromContent($request, 'permissions'));

            return $this->buildSerializedResponse($organizationUser, 'READ_ORGANIZATION_USER');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
