<?php

namespace App\Entity;

use App\Repository\PathRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PathRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"slug", "parent_id"})})
 * @ORM\HasLifecycleCallbacks
 */
#[ORM\Entity(repositoryClass: PathRepository::class)]
#[ORM\UniqueConstraint(columns: ['slug', 'parent_id'])]
#[ORM\HasLifecycleCallbacks]
class Path
{
    private const DEFAULT_PATH_SLUG = 'root';

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public ?string $id = null;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    #[ORM\OneToOne(mappedBy: 'rootPath', targetEntity: Project::class)]
    public ?Project $project = null;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 1, max: 255, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $path;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    #[ORM\ManyToOne(targetEntity: Path::class, inversedBy: 'children')]
    public ?Path $parent = null;

    /**
     * @Serializer\Groups({"READ_PATH"})
     */
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Path::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['path' => 'ASC'])]
    public iterable $children = [];

    /**
     * @Serializer\Groups({"READ_PATH"})
     */
    #[ORM\OneToMany(mappedBy: 'path', targetEntity: Feature::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['title' => 'ASC'])]
    public iterable $features = [];

    /**
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    public string $slug;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public ?Project $rootProject = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->path) ? : self::DEFAULT_PATH_SLUG;
        $this->id = Uuid::v4()->toRfc4122();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->path) ? : self::DEFAULT_PATH_SLUG;
    }
}
