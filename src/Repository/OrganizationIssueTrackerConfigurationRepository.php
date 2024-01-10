<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrganizationIssueTrackerConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrganizationIssueTrackerConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationIssueTrackerConfiguration::class);
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function save(OrganizationIssueTrackerConfiguration $configuration): void
    {
        $this->_em->persist($configuration);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(OrganizationIssueTrackerConfiguration $configuration): void
    {
        $this->_em->remove($configuration);
        $this->_em->flush();
    }
}
