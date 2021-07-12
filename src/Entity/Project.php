<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"organization_id", "slug"})}, indexes={@ORM\Index(columns="slug")})
 */
class Project
{
    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_INTERNAL = 'internal';
    public const VISIBILITY_PRIVATE = 'private';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Doctrine\ORM\Id\UuidGenerator")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT", "READ_STEP"})
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     *
     * @Assert\Length(min=1, max=255)
     * @Assert\NotBlank
     */
    public string $title;

    /**
     * @ORM\Column(type="string", columnDefinition="project_visibility")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public string $visibility;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Path", inversedBy="project", cascade={"all"})
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_PROJECT"})
     */
    public Path $rootPath;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", inversedBy="projects")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public ?Organization $organization = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectUser", mappedBy="project", cascade={"all"}, orphanRemoval=true)
     */
    public iterable $users = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public array $permissions = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT", "READ_STEP"})
     */
    public string $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Step", mappedBy="project", cascade="all", orphanRemoval=true)
     *
     * @Serializer\Exclude
     */
    public iterable $steps = [];

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
