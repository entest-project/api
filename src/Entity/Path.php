<?php

namespace App\Entity;

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
class Path
{
    private const DEFAULT_PATH_SLUG = 'root';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     * @Serializer\Type("string")
     */
    public ?string $id = null;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Project", mappedBy="rootPath")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public ?Project $project = null;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     *
     * @Assert\Length(min=1, max=255, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Path", inversedBy="children")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public ?Path $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Path", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"path": "ASC"})
     *
     * @Serializer\Groups({"READ_PATH"})
     */
    public iterable $children = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feature", mappedBy="path", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"title": "ASC"})
     *
     * @Serializer\Groups({"READ_PATH"})
     */
    public iterable $features = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public string $slug;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public ?Project $rootProject = null;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->slug = Slugify::create()->slugify($this->path) ? : self::DEFAULT_PATH_SLUG;
        $this->id = Uuid::v4()->toRfc4122();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->path) ? : self::DEFAULT_PATH_SLUG;
    }
}
