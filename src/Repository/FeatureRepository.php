<?php

namespace App\Repository;

use App\Entity\Feature;
use Doctrine\ORM\EntityRepository;

class FeatureRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Feature $feature): void
    {
        $this->_em->remove($feature);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Feature $feature): void
    {
        $this->_em->persist($feature);
        $this->_em->flush();
    }
}
