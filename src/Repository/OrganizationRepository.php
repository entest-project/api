<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class OrganizationRepository extends EntityRepository
{
    public function getOrganizationsForUser(User $user): iterable
    {
        return $this
            ->createQueryBuilder('o')
            ->join('o.users', 'ou')
            ->join('ou.user', 'u')
            ->where('u.id = :id')
            ->orderBy('o.name', 'ASC')
            ->setParameter('id', $user->id)
            ->getQuery()
            ->getResult();
    }

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
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Organization $organization): void
    {
        $this->_em->persist($organization);
        $this->_em->flush();
    }
}
