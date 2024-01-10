<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'app_user')]
#[ORM\Index(columns: ['username'])]
#[ORM\Index(columns: ['email'])]
#[ORM\Index(columns: ['reset_password_code'])]
#[ORM\HasLifecycleCallbacks]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     *
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "LIST_PROJECT_USERS", "LIST_USERS", "READ_ORGANIZATION_USER", "READ_PROJECT_USER"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    /**
     * @Serializer\Groups({"LIST_ORGANIZATION_USERS", "LIST_PROJECT_USERS", "LIST_USERS", "READ_ORGANIZATION_USER", "READ_PROJECT_USER"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(min: 1, max: 50, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $username;

    #[ORM\Column(type: 'string')]
    #[Assert\Email]
    #[Assert\Length(min: 1, max: 255, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $email;

    /**
     * @Serializer\Exclude
     */
    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\Length(min: 8, max: 100, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $password;

    #[ORM\Column(type: 'json')]
    public array $roles = ['ROLE_USER'];

    /**
     * @Serializer\Exclude
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProjectUser::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $projects = [];

    /**
     * @Serializer\Exclude
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: OrganizationUser::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $organizations = [];

    /**
     * @Serializer\Exclude
     */
    #[ORM\Column(type: 'datetimetz', nullable: true)]
    public ?\DateTime $lastResetPasswordRequest = null;

    /**
     * @Serializer\Exclude
     */
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
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

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
