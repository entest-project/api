<?php

namespace App\Entity;

use App\Repository\OrganizationUserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: OrganizationUserRepository::class)]
class OrganizationUser
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'users')]
    public Organization $organization;

    /**
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "READ_ORGANIZATION_USER"})
     */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'organizations')]
    public User $user;

    /**
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "READ_ORGANIZATION_USER"})
     */
    #[ORM\Column(type: 'json')]
    public array $permissions = [];
}
