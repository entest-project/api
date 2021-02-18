<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationUserRepository")
 */
class OrganizationUser
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", inversedBy="users")
     */
    public Organization $organization;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="organizations")
     *
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "READ_ORGANIZATION_USER"})
     */
    public User $user;

    /**
     * @ORM\Column(type="json")
     *
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "READ_ORGANIZATION_USER"})
     */
    public array $permissions = [];
}
