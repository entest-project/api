<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Feature
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    public string $id = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Path")
     */
    public Path $path;

    /**
     * @ORM\Column(type="string")
     */
    public string $title;

    /**
     * @ORM\Column(type="string")
     */
    public string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Scenario", mappedBy="feature", cascade={"all"})
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
