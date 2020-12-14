<?php

namespace App\Repository;

use App\Entity\OrganizationUser;
use Doctrine\ORM\EntityRepository;

class OrganizationUserRepository extends EntityRepository
{
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
