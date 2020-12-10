<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_ORGANIZATION"})
     */
    public string $id = '';

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_ORGANIZATION"})
     */
    public string $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="organization", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"title": "ASC"})
     *
     * @Serializer\Groups({"READ_ORGANIZATION"})
     */
    public iterable $projects = [];

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->id = Slugify::create()->slugify($this->name);
    }
}
