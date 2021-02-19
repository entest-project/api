<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 * @ORM\Table(indexes={@ORM\Index(columns="slug")})
 * @ORM\HasLifecycleCallbacks
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Doctrine\ORM\Id\UuidGenerator")
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "LIST_PROJECTS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "LIST_PROJECTS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public string $slug;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public string $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="organization", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"title": "ASC"})
     *
     * @Serializer\Groups({"READ_ORGANIZATION"})
     */
    public iterable $projects = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrganizationUser", mappedBy="organization", cascade={"all"}, orphanRemoval=true)
     */
    public iterable $users = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public array $permissions = [];

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->name);
    }
}
