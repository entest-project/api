<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Path
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_PROJECT"})
     */
    public string $id = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="paths")
     */
    public Project $project;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_PROJECT"})
     */
    public string $path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Path", inversedBy="children")
     */
    public ?Path $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Path", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Groups({"READ_PROJECT"})
     */
    public iterable $children = [];

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->id = sprintf(
            '%s-%s',
            $this->parent === null ? $this->project->id : $this->parent->id,
            Slugify::create()->slugify($this->path)
        );
        if ($this->parent !== null) {
            $this->project = $this->parent->project;
        }
    }
}
