<?php

namespace App\Controller;

use App\Exception\OrganizationNotFoundException;
use App\Exception\UserNotFoundException;
use App\Manager\OrganizationUserManager;
use App\Repository\OrganizationUserRepository;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizations/{organizationId}/users/{userId}', requirements: ['organizationId' => '[a-f0-9-]+', 'userId' => '[a-f0-9-]+'], methods: ['POST'])]
class CreateOrganizationUser extends Api
{
    public function __construct(
        private readonly OrganizationUserManager $organizationUserManager,
        private readonly OrganizationUserRepository $organizationUserRepository
    ) {}

    public function __invoke(string $organizationId, string $userId): Response
    {
        try {
            $organizationUser = $this->organizationUserManager->build($organizationId, $userId);
            $this->denyAccessUnlessGranted(Verb::CREATE, $organizationUser);

            $this->organizationUserRepository->save($organizationUser);

            return $this->buildSerializedResponse($organizationUser, 'READ_ORGANIZATION_USER');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (OrganizationNotFoundException | UserNotFoundException $e) {
            throw new NotFoundHttpException();
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException();
        }
    }
}
