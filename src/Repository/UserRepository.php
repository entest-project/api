<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByEmailOrUsername(User $user): ?User
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.email = :email')
            ->orWhere('u.username = :username')
            ->setParameter('email', $user->email)
            ->setParameter('username', $user->username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function search(string $q): iterable
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.username LIKE :q')
            ->orderBy('u.username', 'ASC')
            ->setParameter('q', sprintf('%%%s%%', $q))
            ->getQuery()
            ->getResult();
    }

    public function searchByOrganization(Organization $organization, string $q): iterable
    {
        return $this
            ->createQueryBuilder('u')
            ->join('u.organizations', 'o')
            ->where('o.organization = :organization')
            ->andWhere('u.username LIKE :q')
            ->orderBy('u.username', 'ASC')
            ->setParameter('organization', $organization)
            ->setParameter('q', sprintf('%%%s%%', $q))
            ->getQuery()
            ->getResult();
    }
}
