<?php

namespace App\Manager;

use App\Entity\OrganizationUser;
use App\Exception\OrganizationNotFoundException;
use App\Exception\UserNotFoundException;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;
use App\Repository\UserRepository;

readonly class OrganizationUserManager
{
    public function __construct(
        private OrganizationRepository $organizationRepository,
        private OrganizationUserRepository $organizationUserRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @throws OrganizationNotFoundException
     * @throws UserNotFoundException
     */
    public function build(string $organizationId, string $userId): OrganizationUser
    {
        $user = $this->userRepository->find($userId);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $organization = $this->organizationRepository->find($organizationId);

        if (null === $organization) {
            throw new OrganizationNotFoundException();
        }

        $organizationUser = new OrganizationUser();
        $organizationUser->organization = $organization;
        $organizationUser->user = $user;

        return $organizationUser;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePermissions(OrganizationUser $organizationUser, array $permissions): void
    {
        $organizationUser->permissions = $permissions;

        $this->organizationUserRepository->save($organizationUser);
    }
}
