<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\UniqueConstraint(columns: ['organization_id', 'slug'])]
#[ORM\Index(columns: ['slug'])]
#[ORM\HasLifecycleCallbacks]
class Project
{
    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_INTERNAL = 'internal';
    public const VISIBILITY_PRIVATE = 'private';

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT", "READ_STEP"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 1, max: 255, normalizer: 'trim')]
    public string $title;

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Column(type: 'string', columnDefinition: 'project_visibility')]
    public string $visibility;

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_PROJECT"})
     */
    #[ORM\OneToOne(inversedBy: 'project', targetEntity: Path::class, cascade: ['all'])]
    public Path $rootPath;

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'projects')]
    public ?Organization $organization = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectUser::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $users = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public array $permissions = [];

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT", "READ_STEP"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    public string $slug;

    /**
     * @Serializer\Exclude
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Step::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $steps = [];

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
        $this->id = Uuid::v4()->toRfc4122();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
