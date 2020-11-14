<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Project $project)
    {
        $this->_em->persist($project);
        $this->_em->flush();
    }
}
