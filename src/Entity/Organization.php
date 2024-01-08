<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganizationRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"slug"})})
 * @ORM\HasLifecycleCallbacks
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "LIST_PROJECTS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     * @Serializer\Type("string")
     */
    public string $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "LIST_PROJECTS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public string $slug;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     *
     * @Assert\Length(min=1, max=255, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
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
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OrganizationIssueTrackerConfiguration", mappedBy="organization", cascade={"all"}, orphanRemoval=true)
     */
    public iterable $issueTrackerConfigurations = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public array $permissions = [];

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->name);
        $this->id = Uuid::v4()->toRfc4122();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->name);
    }
}
