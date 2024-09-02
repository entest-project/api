<?php

namespace App\Entity;

use App\Repository\OrganizationUserRepository;
use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: OrganizationUserRepository::class)]
class OrganizationUser
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'users')]
    public Organization $organization;

    #[Serializer\Groups([Groups::ListOrganizationUsers->value, Groups::ReadOrganizationUser->value])]
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'organizations')]
    public User $user;

    #[Serializer\Groups([Groups::ListOrganizationUsers->value, Groups::ReadOrganizationUser->value])]
    #[ORM\Column(type: 'json')]
    public array $permissions = [];
}
