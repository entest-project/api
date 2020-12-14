<?php

namespace App\Repository;

use App\Entity\ProjectUser;
use Doctrine\ORM\EntityRepository;

class ProjectUserRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ProjectUser $projectUser): void
    {
        $this->_em->remove($projectUser);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ProjectUser $projectUser): void
    {
        $this->_em->persist($projectUser);
        $this->_em->flush();
    }
}
