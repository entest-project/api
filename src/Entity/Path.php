<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PathRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Path
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public string $id = '';

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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if ($this->parent !== null) {
            $this->id = sprintf(
                '%s-%s',
                $this->parent->id,
                Slugify::create()->slugify($this->path)
            );
        }
    }
}
