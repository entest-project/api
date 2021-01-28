<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\OrganizationUser;
use App\Entity\User;
use App\Security\OrganizationPermission;
use Doctrine\ORM\EntityRepository;

class OrganizationUserRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function makeAdmin(User $user, Organization $organization): void
    {
        $organizationUser = new OrganizationUser();
        $organizationUser->organization = $organization;
        $organizationUser->user = $user;
        $organizationUser->permissions = [OrganizationPermission::ADMIN];

        $this->save($organizationUser);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(OrganizationUser $organizationUser): void
    {
        $this->_em->remove($organizationUser);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(OrganizationUser $organizationUser): void
    {
        $this->_em->persist($organizationUser);
        $this->_em->flush();
    }
}
