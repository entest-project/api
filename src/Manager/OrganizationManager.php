<?php

namespace App\Manager;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;

class OrganizationManager
{
    private OrganizationRepository $organizationRepository;

    private OrganizationUserRepository $organizationUserRepository;

    public function __construct(OrganizationRepository $organizationRepository, OrganizationUserRepository $organizationUserRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrganization(Organization $organization, User $user): void
    {
        $this->organizationRepository->save($organization);
        $this->organizationUserRepository->makeAdmin($user, $organization);
    }
}
