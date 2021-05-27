<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Doctrine\ORM\Id\UuidGenerator")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     * @Serializer\Type("string")
     */
    public $id;

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
     */
    public string $title;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $description = '';

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
    public string $status;

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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
