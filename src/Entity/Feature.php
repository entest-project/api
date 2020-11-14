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
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="features")
     */
    public Project $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Path")
     */
    public Path $path;

    /**
     * @ORM\Column(type="string")
     */
    public string $slug = '';

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

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
