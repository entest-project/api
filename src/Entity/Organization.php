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
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator")
     *
     * @Serializer\Exclude
     */
    public string $id = '';

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH"})
     */
    public string $slug;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH",})
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
     * @ORM\OneToMany(targetEntity="App\Entity\OrganizationUser", mappedBy="organization", cascade={"all"}, orphanRemoval=true)
     */
    public iterable $users = [];

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->name);
    }
}
