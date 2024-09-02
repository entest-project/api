<?php

namespace App\Entity;

use App\Repository\ProjectUserRepository;
use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: ProjectUserRepository::class)]
#[ORM\Index(columns: ['token'])]
class ProjectUser
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'users')]
    public Project $project;

    #[Serializer\Groups([Groups::ListProjectUsers->value, Groups::ReadProjectUser->value])]
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    public User $user;

    #[Serializer\Groups([Groups::ListProjectUsers->value, Groups::ReadProjectUser->value])]
    #[ORM\Column(type: 'json')]
    public array $permissions = [];

    #[Serializer\Groups([Groups::ReadProjectUser->value])]
    #[ORM\Column(type: 'text')]
    public string $token = '';
}
