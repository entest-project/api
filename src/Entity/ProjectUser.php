<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectUserRepository")
 */
class ProjectUser
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="users")
     */
    public Project $project;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     *
     * @Serializer\Groups({"LIST_PROJECT_USERS", "READ_PROJECT_USER"})
     */
    public User $user;

    /**
     * @ORM\Column(type="json")
     *
     * @Serializer\Groups({"LIST_PROJECT_USERS", "READ_PROJECT_USER"})
     */
    public array $permissions = [];

    /**
     * @@ORM\Column(type="text")
     *
     * @Serializer\Groups({"READ_PROJECT_USER_TOKEN"})
     */
    public string $token = '';
}
