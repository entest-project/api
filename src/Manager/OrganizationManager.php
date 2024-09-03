<?php

namespace App\Manager;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;

readonly class OrganizationManager
{
    public function __construct(
        private OrganizationRepository $organizationRepository,
        private OrganizationUserRepository $organizationUserRepository,
        private ProjectRepository $projectRepository
    ) {
    }

    /**
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrganization(Organization $organization, User $user): void
    {
        $this->organizationRepository->save($organization);
        $this->organizationUserRepository->makeAdmin($user, $organization);
    }

    /**
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getOrganizationProjects(Organization $organization, ?User $user): iterable
    {
        if (null === $user) {
            return $this->projectRepository->findOrganizationPublicProjects($organization);
        }

        return $this->projectRepository->findOrganizationProjectsForUser($user, $organization);
    }
}
