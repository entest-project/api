<?php

namespace App\Entity;

use App\Repository\FeatureRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeatureRepository::class)]
#[ORM\UniqueConstraint(columns: ['slug', 'path_id'])]
#[ORM\HasLifecycleCallbacks]
class Feature
{
    public const FEATURE_STATUS_DRAFT = 'draft';

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public ?string $id = null;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\ManyToOne(targetEntity: Path::class, inversedBy: 'features')]
    public Path $path;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 1, max: 255, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $title;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Length(max: 1024, normalizer: 'trim')]
    public string $description = "As an <actor>\nI want to <action>\nSo that <consequence>";

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\OneToMany(mappedBy: 'feature', targetEntity: Scenario::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['priority' => 'ASC'])]
    public iterable $scenarios = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    #[ORM\Column(type: 'string', length: 255)]
    public string $slug;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    #[ORM\Column(type: 'string', columnDefinition: 'feature_status')]
    public string $status = self::FEATURE_STATUS_DRAFT;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
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

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->slug = Slugify::create()->slugify($this->title);
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
