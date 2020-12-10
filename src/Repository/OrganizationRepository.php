<?php

namespace App\Repository;

use App\Entity\Organization;
use Doctrine\ORM\EntityRepository;

class OrganizationRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Organization $organization): void
    {
        $this->_em->remove($organization);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Organization $organization): void
    {
        $this->_em->persist($organization);
        $this->_em->flush();
    }
}
