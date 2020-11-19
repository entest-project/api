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
    public function save(Feature $feature)
    {
        $this->_em->persist($feature);
        $this->_em->flush();
    }
}
