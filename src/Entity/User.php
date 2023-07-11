<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user", indexes={@ORM\Index(columns={"username"}), @ORM\Index(columns={"email"}), @ORM\Index(columns={"reset_password_code"})})
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     *
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "LIST_PROJECT_USERS", "LIST_USERS", "READ_ORGANIZATION_USER", "READ_PROJECT_USER"})
     * @Serializer\Type("string")
     */
    public string $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "LIST_PROJECT_USERS", "LIST_USERS", "READ_ORGANIZATION_USER", "READ_PROJECT_USER"})
     *
     * @Assert\Length(min=1, max=50, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $username;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Email
     * @Assert\Length(min=1, max=255, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $email;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Serializer\Exclude
     *
     * @Assert\Length(min=8, max=100, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
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

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     *
     * @Serializer\Exclude
     */
    public ?\DateTime $lastResetPasswordRequest = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Serializer\Exclude
     */
    public ?string $resetPasswordCode = null;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
