<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="app_user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator")
     *
     * @Serializer\Type("string")
     */
    public Ulid $id;

    /**
     * @ORM\Column(type="string", length=255)
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
