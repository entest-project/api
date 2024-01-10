<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\Index(columns: ['slug'])]
#[ORM\HasLifecycleCallbacks]
class Organization
{
    /**
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "LIST_PROJECTS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT", "READ_ORGANIZATION_ISSUE_TRACKER_CONFIGURATION"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    /**
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "LIST_PROJECTS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    public string $slug;

    /**
     * @Serializer\Groups({"LIST_ORGANIZATIONS", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 1, max: 255, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $name;

    /**
     * @Serializer\Groups({"READ_ORGANIZATION"})
     */
    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: Project::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['title' => 'ASC'])]
    public iterable $projects = [];

    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: OrganizationUser::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $users = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\OneToMany(mappedBy: 'organization', targetEntity: OrganizationIssueTrackerConfiguration::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $issueTrackerConfigurations = [];

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public array $permissions = [];

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->slug = Slugify::create()->slugify($this->name);
        $this->id = Uuid::v4()->toRfc4122();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->slug = Slugify::create()->slugify($this->name);
    }
}
