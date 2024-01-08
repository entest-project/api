<?php

namespace App\Entity;

use App\Repository\ProjectUserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: ProjectUserRepository::class)]
#[ORM\Index(columns: ['token'])]
class ProjectUser
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'users')]
    public Project $project;

    /**
     * @Serializer\Groups({"LIST_PROJECT_USERS", "READ_PROJECT_USER"})
     */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    public User $user;

    /**
     * @Serializer\Groups({"LIST_PROJECT_USERS", "READ_PROJECT_USER"})
     */
    #[ORM\Column(type: 'json')]
    public array $permissions = [];

    /**
     * @Serializer\Groups({"READ_PROJECT_USER_TOKEN"})
     */
    #[ORM\Column(type: 'text')]
    public string $token = '';
}
