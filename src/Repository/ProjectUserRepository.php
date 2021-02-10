<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use App\Security\ProjectPermission;
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

    public function findOneByUserAndProject(User $user, Project $project): ?ProjectUser
    {
        return $this->findOneBy(['user' => $user, 'project' => $project]);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function makeAdmin(User $user, Project $project): void
    {
        $projectUser = new ProjectUser();
        $projectUser->project = $project;
        $projectUser->user = $user;
        $projectUser->permissions = [ProjectPermission::ADMIN];

        $this->save($projectUser);
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
