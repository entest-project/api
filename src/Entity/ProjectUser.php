<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
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
     */
    public User $user;

    /**
     * @ORM\Column(type="json")
     *
     * @Serializer\Groups({})
     */
    public array $permissions = [];
}
