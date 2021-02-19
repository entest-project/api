<?php

namespace App\Manager;

use App\Entity\ProjectUser;
use App\Exception\ProjectNotFoundException;
use App\Exception\UserNotFoundException;
use App\Repository\ProjectRepository;
use App\Repository\ProjectUserRepository;
use App\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

class ProjectUserManager
{
    private ProjectRepository $projectRepository;

    private ProjectUserRepository $projectUserRepository;

    private UserRepository $userRepository;

    public function __construct(ProjectRepository $projectRepository, ProjectUserRepository $projectUserRepository, UserRepository $userRepository)
    {
        $this->projectRepository = $projectRepository;
        $this->projectUserRepository = $projectUserRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ProjectNotFoundException
     * @throws UserNotFoundException
     */
    public function build(string $projectId, string $userId): ProjectUser
    {
        $user = $this->userRepository->find($userId);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $project = $this->projectRepository->find($projectId);

        if (null === $project) {
            throw new ProjectNotFoundException();
        }

        $projectUser = new ProjectUser();
        $projectUser->project = $project;
        $projectUser->user = $user;

        return $projectUser;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function buildToken(ProjectUser $projectUser): void
    {
        if ($projectUser->token) {
            return;
        }

        $projectUser->token = Uuid::v6()->toRfc4122();

        $this->projectUserRepository->save($projectUser);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePermissions(ProjectUser $projectUser, array $permissions): void
    {
        $projectUser->permissions = $permissions;

        $this->projectUserRepository->save($projectUser);
    }
}
