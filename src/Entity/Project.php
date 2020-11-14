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
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"collection", "display"})
     */
    public int $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"collection", "display"})
     */
    public string $slug = '';

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"collection", "display"})
     */
    public string $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feature", mappedBy="project", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Groups({"display"})
     */
    public iterable $features = [];

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->title);
    }
}
