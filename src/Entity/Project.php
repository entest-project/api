<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_PROJECT"})
     */
    public string $id = '';

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_PROJECT"})
     */
    public string $title;

    /**
     * @var Path[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Path", mappedBy="project", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Groups({"READ_PROJECT"})
     */
    public iterable $paths = [];

    /**
     * @Serializer\Groups({"READ_PROJECT"})
     * @Serializer\VirtualProperty("tree")
     */
    public function getTree(): array
    {
        $tree = [];
        foreach ($this->paths as $path) {
            if ($path->parent === null) {
                $tree[] = $path;
            }
        }

        return $tree;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->id = Slugify::create()->slugify($this->title);
    }
}
