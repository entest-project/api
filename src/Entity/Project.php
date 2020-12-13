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
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT", "READ_STEP"})
     */
    public string $id = '';

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public string $title;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Path", inversedBy="project", cascade={"all"})
     *
     * @Serializer\Groups({"LIST_PROJECTS", "READ_PROJECT"})
     */
    public Path $rootPath;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", inversedBy="projects")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_PATH", "READ_PROJECT"})
     */
    public ?Organization $organization = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectUser", mappedBy="project", cascade={"all"}, orphanRemoval=true)
     */
    public iterable $users = [];

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->id = Slugify::create()->slugify($this->title);
        $this->rootPath->id = $this->id;
    }
}
