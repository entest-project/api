<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeatureRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Feature
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH"})
     */
    public string $id = '';

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
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public iterable $scenarios = [];

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->id = sprintf(
            '%s-%s-',
            $this->path->id,
            Slugify::create()->slugify($this->title)
        );
    }
}
