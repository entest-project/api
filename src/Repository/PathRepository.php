<?php

namespace App\Repository;

use App\Entity\Path;
use Doctrine\ORM\EntityRepository;

class PathRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Path $path): void
    {
        $this->_em->remove($path);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Path $path): void
    {
        $this->_em->persist($path);
        $this->_em->flush();
    }
}
