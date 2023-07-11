<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeatureRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"slug", "path_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Feature
{
    public const FEATURE_STATUS_DRAFT = 'draft';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     * @Serializer\Type("string")
     */
    public ?string $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Path", inversedBy="features")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public Path $path;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     *
     * @Assert\Length(min=1, max=255, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $title;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     *
     * @Assert\Length(max=1024, normalizer="trim")
     */
    public string $description = "As an <actor>\nI want to <action>\nSo that <consequence>";

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Scenario", mappedBy="feature", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"priority": "ASC"})
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public iterable $scenarios = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public string $slug;

    /**
     * @ORM\Column(type="string", columnDefinition="feature_status")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public string $status = self::FEATURE_STATUS_DRAFT;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public iterable $tags = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public ?Project $rootProject = null;

    public function getRootPath(): string
    {
        $rootPath = sprintf('/%s', $this->slug);

        $path = $this->path;
        while ($path->project === null) {
            $rootPath = sprintf('/%s%s', $path->slug, $rootPath);
            $path = $path->parent;
        }

        return $rootPath;
    }

    public function getDisplayRootPath(): string
    {
        $rootPath = sprintf('%s', $this->title);

        $path = $this->path;
        while ($path->project === null) {
            $rootPath = sprintf('%s / %s', $path->path, $rootPath);
            $path = $path->parent;
        }

        return $rootPath;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->slug = Slugify::create()->slugify($this->title);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
