<?php

namespace App\Repository;

use App\Entity\Step;
use Doctrine\ORM\EntityRepository;

class StepRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Step $step): void
    {
        $this->_em->persist($step);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Step $step): void
    {
        $this->_em->remove($step);
        $this->_em->flush();
    }
}
