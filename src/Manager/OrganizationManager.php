<?php

namespace App\Manager;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;
use App\Repository\ProjectRepository;

class OrganizationManager
{
    private OrganizationRepository $organizationRepository;

    private OrganizationUserRepository $organizationUserRepository;

    private ProjectRepository $projectRepository;

    public function __construct(
        OrganizationRepository $organizationRepository,
        OrganizationUserRepository $organizationUserRepository,
        ProjectRepository $projectRepository
    ) {
        $this->organizationRepository = $organizationRepository;
        $this->organizationUserRepository = $organizationUserRepository;
        $this->projectRepository = $projectRepository;
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
