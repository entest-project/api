<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Doctrine\ORM\Id\UuidGenerator")
     *
     * @Serializer\Groups({"LIST_PROJECT_USERS", "LIST_USERS", "READ_PROJECT_USER"})
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"LIST_PROJECT_USERS", "LIST_USERS", "READ_PROJECT_USER"})
     */
    public string $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $email;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Serializer\Exclude
     */
    public string $password;

    /**
     * @ORM\Column(type="json")
     */
    public array $roles = ['ROLE_USER'];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectUser", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Exclude
     */
    public iterable $projects = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrganizationUser", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Exclude
     */
    public iterable $organizations = [];

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }
}
