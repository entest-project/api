<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\UniqueConstraint(fields: ['organization', 'issueTracker'])]
#[ORM\HasLifecycleCallbacks]
class OrganizationIssueTrackerConfiguration
{
    /**
     * @Serializer\Groups({"LIST_ORGANIZATION_ISSUE_TRACKER_CONFIGURATIONS", "READ_ORGANIZATION_ISSUE_TRACKER_CONFIGURATION", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    /**
     * @Serializer\Groups({"READ_ORGANIZATION_ISSUE_TRACKER_CONFIGURATION", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
     #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'issueTrackerConfigurations')]
     #[ORM\JoinColumn(nullable: false)]
     #[Assert\NotNull]
    public Organization $organization;

    /**
     * @Serializer\Exclude
     */
     #[ORM\Column(type: 'string', enumType: IssueTracker::class)]
     #[Assert\NotBlank]
    public IssueTracker $issueTracker;

    /**
     * @Serializer\Groups({"READ_ORGANIZATION_ISSUE_TRACKER_CONFIGURATION", "LIST_ORGANIZATION_ISSUE_TRACKER_CONFIGURATIONS"})
     * @Serializer\Type("string")
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public string $apiUrl;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public string $accessToken;

    /**
     * @Serializer\VirtualProperty(name="issueTracker")
     * @Serializer\Groups({"LIST_ORGANIZATION_ISSUE_TRACKER_CONFIGURATIONS", "READ_ORGANIZATION_ISSUE_TRACKER_CONFIGURATION", "READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public function issueTracker(): string
    {
        return $this->issueTracker->value;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
