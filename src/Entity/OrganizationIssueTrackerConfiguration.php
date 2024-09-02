<?php

declare(strict_types=1);

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\UniqueConstraint(fields: ['organization', 'issueTracker'])]
#[ORM\HasLifecycleCallbacks]
class OrganizationIssueTrackerConfiguration
{
    #[Serializer\Groups([
        Groups::ListOrganizationIssueTrackerConfigurations->value,
        Groups::ReadOrganizationIssueTrackerConfiguration->value,
        Groups::ReadFeature->value,
        Groups::ReadOrganization->value,
        Groups::ReadPath->value,
        Groups::ReadProject->value,
        Groups::ReadFeatureIssueTrackerConfiguration->value
    ])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[Serializer\Groups([
        Groups::ReadOrganizationIssueTrackerConfiguration->value,
    ])]
    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'issueTrackerConfigurations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    public Organization $organization;

    #[Serializer\Groups([
        Groups::ListOrganizationIssueTrackerConfigurations->value,
        Groups::ReadOrganizationIssueTrackerConfiguration->value,
        Groups::ReadFeature->value,
        Groups::ReadOrganization->value,
        Groups::ReadPath->value,
        Groups::ReadProject->value,
        Groups::ReadFeatureIssueTrackerConfiguration->value
    ])]
    #[ORM\Column(type: 'string', enumType: IssueTracker::class)]
    #[Assert\NotBlank]
    public IssueTracker $issueTracker;

    #[Serializer\Groups([
        Groups::ReadOrganizationIssueTrackerConfiguration->value,
        Groups::ListOrganizationIssueTrackerConfigurations->value
    ])]
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public string $apiUrl;

    #[Serializer\Groups([
        Groups::ReadOrganizationIssueTrackerConfiguration->value,
        Groups::ListOrganizationIssueTrackerConfigurations->value
    ])]
    #[ORM\Column(type: 'string', length: 255)]
    public string $userIdentifier = '';

    #[Serializer\Groups([
        Groups::ReadOrganizationIssueTrackerConfiguration->value,
        Groups::ListOrganizationIssueTrackerConfigurations->value
    ])]
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public string $accessToken;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
